<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Auth;

/**
 * \Playground\Auth\Permission
 */
class Permission
{
    protected bool $allowed = false;

    protected mixed $id = null;

    protected bool $isGuest = false;

    protected string $verify = '';

    public function __construct(string $verify)
    {
        $this->verify = $verify;
    }

    public function markAllowed(): self
    {
        $this->allowed = true;

        return $this;
    }

    public function markIsGuest(): self
    {
        $this->isGuest = true;

        return $this;
    }

    public function allowed(): bool
    {
        return $this->allowed;
    }

    public function isGuest(): bool
    {
        return $this->isGuest;
    }
}
