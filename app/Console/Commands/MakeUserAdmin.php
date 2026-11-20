<?php

namespace App\Console\Commands;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('user:make-admin {email : Email akun yang akan dijadikan admin}')]
#[Description('Promosikan akun yang sudah terdaftar menjadi admin')]
class MakeUserAdmin extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = strtolower((string) $this->argument('email'));
        $user = User::query()->where('email', $email)->first();

        if (! $user) {
            $this->error('Akun tidak ditemukan.');

            return self::FAILURE;
        }

        $user->role = Role::ADMIN->value;
        $user->save();

        $this->info("{$user->email} sekarang ber-role admin.");

        return self::SUCCESS;
    }
}
