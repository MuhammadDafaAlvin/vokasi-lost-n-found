<?php

namespace Tests;

use App\Enums\Role;
use App\Models\User;

trait TestAuth
{
    /**
     * Currently authenticated user.
     */
    protected private(set) User $user;

    /**
     * Set user and authenticate them.
     */
    public function authenticate(User $user): void
    {
        $this->user = $user;

        $this->actingAs($user);
    }

    /**
     * Seed a user with role superadmin.
     */
    public function createSuperAdmin(): User
    {
        return User::factory()->role(Role::ADMIN->value)->create();
    }

    /**
     * Seed and authenticate a user with role superadmin.
     */
    public function actingAsSuperAdmin(): void
    {
        $this->authenticate($this->createSuperAdmin());
    }
}
