<?php
/**
 * Playground
 */
namespace Playground\Auth\Policies;

use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * \Playground\Auth\Policies\RoleTrait
 */
trait RoleTrait
{
    /**
     * @var array<int, string> The roles allowed for actions in the MVC.
     */
    protected $rolesForAction = [
        'admin',
        'wheel',
        'root',
    ];

    /**
     * @var array<int, string> The roles allowed for admin actions in the MVC.
     */
    protected $rolesForAdmin = [
        'admin',
        'wheel',
        'root',
    ];

    /**
     * @var array<int, string> The roles allowed to view the MVC.
     */
    protected $rolesToView = [
        'admin',
        'wheel',
        'root',
    ];

    /**
     * Get the roles for admin actions.
     *
     * @return array<int, string>
     */
    public function getRolesForAdmin(): array
    {
        return $this->rolesForAdmin;
    }

    /**
     * Get the roles for standard actions.
     *
     * @return array<int, string>
     */
    public function getRolesForAction(): array
    {
        return $this->rolesForAction;
    }

    public function isRoot(Authenticatable $user): bool
    {
        $isRoot = false;

        if (! empty(config('playground.auth.userRole'))) {
            $isRoot = $user->getAttributeValue('role') === 'root';
        }

        return $isRoot;
    }

    /**
     * Get the roles for view actions.
     *
     * @return array<int, string>
     */
    public function getRolesToView(): array
    {
        return $this->rolesToView;
    }

    public function hasRole(Authenticatable $user, string $ability): bool|Response
    {
        if (in_array($ability, [
            'show',
            'detail',
            'index',
            'view',
            'viewAny',
        ])) {
            $roles = $this->getRolesToView();
        } elseif (in_array($ability, [
            'create',
            'edit',
            'manage',
            'store',
            'update',
        ])) {
            $roles = $this->getRolesForAction();
        } elseif (in_array($ability, [
            'delete',
            'forceDelete',
            'lock',
            'unlock',
            'restore',
        ])) {
            $roles = $this->getRolesForAdmin();
        } else {
            $roles = $this->getRolesForAdmin();
            // // Invalid role
            // return Response::denyWithStatus(406, __('playground::auth.unacceptable'));
        }

        if (config('playground.auth.hasRole') && method_exists($user, 'hasRole')) {
            // Check for any role.
            foreach ($roles as $role) {
                if ($user->hasRole($role)) {
                    return true;
                }
            }
        }

        if (config('playground.auth.userRole')) {
            // Check for any role.
            foreach ($roles as $role) {
                if (! empty($user->role) && $role === $user->role) {
                    return true;
                }
            }
        }

        if (config('playground.auth.userRoles')) {
            if (is_array($roles)
                && ! empty($user->roles)
            && is_array($user->roles)
            ) {
                foreach ($roles as $role) {
                    if (in_array($role, $user->roles)) {
                        return true;
                    }
                }
            }
        }

        // dd([
        //     '__METHOD__' => __METHOD__,
        //     '__METHOD__' => __METHOD__,
        //     '$user' => $user,
        //     'userRole' => config('playground.auth.userRole'),
        //     'userRoles' => config('playground.auth.userRoles'),
        //     'hasRole' => config('playground.auth.hasRole') && method_exists($user, 'hasRole'),
        //     '$ability' => $ability,
        //     '$roles' => $roles,
        // ]);
        return Response::denyWithStatus(401, __('playground::auth.unauthorized'));
    }
}
