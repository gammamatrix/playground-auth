<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Auth\Policies;

use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * \Playground\Auth\Policies\ModelPolicy
 */
abstract class ModelPolicy extends Policy
{
    /**
     * Determine whether the user can create model.
     */
    public function create(Authenticatable $user): bool|Response
    {
        return $this->verify($user, 'create');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * - This is for soft deletes or trash.
     */
    public function delete(
        Authenticatable $user,
        Model $model
    ): bool|Response {
        // Models must be unlocked to allow deleting.
        // NOTE: This lock check is bypassed by a root user.
        if ($model->getAttribute('locked')) {
            // return Response::denyWithStatus(423);
            return Response::denyWithStatus(423, __('playground-auth::auth.model.locked', [
                'model' => Str::of(class_basename($model))
                    ->snake()->replace('_', ' ')->title()->lower()->toString(),
            ]));
        }

        return $this->verify($user, 'delete');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function detail(Authenticatable $user, Model $model): bool|Response
    {
        // dd([
        //     '__METHOD__' => __METHOD__,
        //     '__FILE__' => __FILE__,
        //     '__LINE__' => __LINE__,
        //     'static::class' => static::class,
        //     '$user' => $user->toArray(),
        //     '$this->allowRootOverride' => $this->allowRootOverride,
        //     '$this->package' => $this->package,
        //     '$this->entity' => $this->entity,
        //     'config(playground-auth)' => config('playground-auth'),
        // ]);
        return $this->verify($user, 'view');
    }

    /**
     * Determine whether the user can edit a model.
     */
    public function edit(Authenticatable $user, Model $model = null): bool|Response
    {
        return $this->verify($user, 'edit');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * Force deletes permanently from a database.
     */
    public function forceDelete(Authenticatable $user, Model $model): bool|Response
    {
        return $this->verify($user, 'forceDelete');
    }

    /**
     * Determine whether the user can lock a model.
     */
    public function lock(Authenticatable $user, Model $model): bool|Response
    {
        return $this->verify($user, 'lock');
    }

    /**
     * Determine whether the user can manage the model.
     */
    public function manage(Authenticatable $user, Model $model): bool|Response
    {
        return $this->verify($user, 'manage');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Authenticatable $user, Model $model): bool|Response
    {
        return $this->verify($user, 'restore');
    }

    /**
     * Determine whether the user can store the model.
     */
    public function store(Authenticatable $user): bool|Response
    {
        return $this->verify($user, 'store');
    }

    /**
     * Determine whether the user can edit a model.
     */
    public function update(Authenticatable $user, Model $model): bool|Response
    {
        // dd([
        //     '__METHOD__' => __METHOD__,
        //     '__FILE__' => __FILE__,
        //     '__LINE__' => __LINE__,
        //     'static::class' => static::class,
        //     '$user' => $user->toArray(),
        //     '$this->allowRootOverride' => $this->allowRootOverride,
        //     '$this->package' => $this->package,
        //     '$this->entity' => $this->entity,
        //     'config(playground-auth)' => config('playground-auth'),
        // ]);
        // Models must be unlocked to allow updating.
        // NOTE: This lock check is bypassed by a root user.
        if ($model->getAttribute('locked')) {
            // return Response::denyWithStatus(423);
            return Response::denyWithStatus(423, __('playground-auth::auth.model.locked', [
                'model' => Str::of(class_basename($model))->snake()->replace('_', ' ')->title()->lower()->toString(),
            ]));
        }

        return $this->verify($user, 'update');
    }

    /**
     * Determine whether the user can unlock a model.
     */
    public function unlock(Authenticatable $user, Model $model): bool|Response
    {
        return $this->verify($user, 'unlock');
    }
}
