<?php

namespace Tests\Unit;

use App\Enums\Role;
use App\Models\Comment;
use App\Models\Report;
use App\Models\User;
use App\Policies\CommentPolicy;
use App\Policies\ReportPolicy;
use App\Providers\AppServiceProvider;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Password;
use Tests\TestCase;

class AppServiceProviderCoverageTest extends TestCase
{
    public function test_provider_registers_policies_and_testing_defaults(): void
    {
        $provider = new AppServiceProvider($this->app);
        $provider->register();
        $provider->boot();

        $member = User::factory()->create();
        $admin = User::factory()->role(Role::ADMIN->value)->create();

        $this->assertFalse(Gate::forUser($member)->allows('access-admin'));
        $this->assertTrue(Gate::forUser($admin)->allows('access-admin'));
        $this->assertInstanceOf(ReportPolicy::class, Gate::getPolicyFor(Report::class));
        $this->assertInstanceOf(CommentPolicy::class, Gate::getPolicyFor(Comment::class));
        $this->assertInstanceOf(CarbonImmutable::class, Date::now());
        $this->assertSame('minlength: 8;', Password::default()->toPasswordRulesString());
    }

    public function test_provider_applies_production_password_defaults(): void
    {
        $originalEnvironment = $this->app->environment();

        try {
            $this->app->detectEnvironment(
                static fn (): string => 'production',
            );

            (new AppServiceProvider($this->app))->boot();

            $passwordRules = Password::default()->toPasswordRulesString();

            $this->assertStringContainsString('minlength: 12', $passwordRules);
            $this->assertStringContainsString('required: lower', $passwordRules);
            $this->assertStringContainsString('required: upper', $passwordRules);
            $this->assertStringContainsString('required: digit', $passwordRules);
            $this->assertStringContainsString('required: special', $passwordRules);
        } finally {
            $this->app->detectEnvironment(
                static fn (): string => $originalEnvironment,
            );
            DB::prohibitDestructiveCommands(false);
        }
    }
}
