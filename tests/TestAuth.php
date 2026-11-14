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
     * Seed a user with role admin.
     */
    public function createAdmin(): User
    {
        return User::factory()->role(Role::ADMIN->value)->create();
    }

    /**
     * Seed a user with role member.
     */
    public function createMember(): User
    {
        return User::factory()->role(Role::MEMBER->value)->create();
    }

    /**
     * Seed and authenticate a user with role admin.
     */
    public function actingAsAdmin(): void
    {
        $this->authenticate($this->createAdmin());
    }

    /**
     * Seed and authenticate a user with role member.
     */
    public function actingAsMember(): void
    {
        $this->authenticate($this->createMember());
    }
}
