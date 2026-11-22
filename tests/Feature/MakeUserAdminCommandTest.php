<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Tests\TestCase;

class MakeUserAdminCommandTest extends TestCase
{
    public function test_command_promotes_an_existing_account_to_admin(): void
    {
        $user = User::factory()->create([
            'email' => 'coverage-member@mhs.unesa.ac.id',
        ]);

        $this->artisan('user:make-admin', [
            'email' => strtoupper($user->email),
        ])
            ->expectsOutput($user->email.' sekarang ber-role admin.')
            ->assertExitCode(Command::SUCCESS);

        $this->assertSame(Role::ADMIN->value, $user->fresh()->role);
    }

    public function test_command_reports_when_account_does_not_exist(): void
    {
        $this->artisan('user:make-admin', [
            'email' => 'missing@mhs.unesa.ac.id',
        ])
            ->expectsOutput('Akun tidak ditemukan.')
            ->assertExitCode(Command::FAILURE);
    }
}
