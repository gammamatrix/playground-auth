<?php
/**
 * Playground
 */
namespace Playground\Auth\Policies\Contracts;

use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * \Playground\Auth\Policies\Contracts\Role
 */
interface Role
{
    /**
     * Get the roles for admin actions.
     *
     * @return array<int, string>
     */
    public function getRolesForAdmin(): array;

    /**
     * Get the roles for standard actions.
     *
     * @return array<int, string>
     */
    public function getRolesForAction(): array;

    public function isRoot(Authenticatable $user): bool;

    /**
     * Get the roles for view actions.
     *
     * @return array<int, string>
     */
    public function getRolesToView(): array;

    public function hasRole(Authenticatable $user, string $ability): bool|Response;
}
