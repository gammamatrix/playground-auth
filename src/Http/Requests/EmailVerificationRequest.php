<?php
/**
 * Playground
 *
 */

namespace GammaMatrix\Playground\Auth\Http\Requests;

use Illuminate\Foundation\Auth\EmailVerificationRequest as BaseRequest;

/**
 * \GammaMatrix\Playground\Auth\Http\Requests\EmailVerificationRequest
 *
 */
class EmailVerificationRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();

        if (!$user
            || ! hash_equals((string) $this->route('id'), (string) $user->id)
        ) {
            return false;
        }

        if (! hash_equals((string) $this->route('hash'), sha1($user->email))) {
            return false;
        }

        return true;
    }
}
