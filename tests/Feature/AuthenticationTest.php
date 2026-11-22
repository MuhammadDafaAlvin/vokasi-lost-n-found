<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_guest_can_view_login_and_register_pages(): void
    {
        $this->get(route('login.create'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('auth/login'));

        $this->get(route('register.create'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('auth/register')
                ->has('registrationAvailable'));
    }

    public function test_registration_creates_member_and_ignores_submitted_role(): void
    {
        config(['auth.unesa_email_domains' => ['mhs.unesa.ac.id']]);

        $response = $this->post(route('register.store'), [
            'name' => 'Test Member',
            'email' => 'test-member@mhs.unesa.ac.id',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => Role::ADMIN->value,
        ]);

        $user = User::query()
            ->where('email', 'test-member@mhs.unesa.ac.id')
            ->firstOrFail();

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
        $this->assertSame(Role::MEMBER->value, $user->role);
    }

    public function test_registration_rejects_invalid_input_and_domain(): void
    {
        config(['auth.unesa_email_domains' => ['mhs.unesa.ac.id']]);

        $this->from(route('register.create'))
            ->post(route('register.store'), [
                'name' => '',
                'email' => 'not-an-email',
                'password' => 'short',
                'password_confirmation' => 'different',
            ])
            ->assertRedirect(route('register.create'))
            ->assertSessionHasErrors(['name', 'email', 'password']);

        $this->from(route('register.create'))
            ->post(route('register.store'), [
                'name' => 'Outside User',
                'email' => 'outside@example.com',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
            ])
            ->assertRedirect(route('register.create'))
            ->assertSessionHasErrors('email');

        $this->assertDatabaseCount('users', 0);
    }

    public function test_registration_is_unavailable_without_configured_domain(): void
    {
        config(['auth.unesa_email_domains' => []]);

        $this->post(route('register.store'), [
            'name' => 'No Domain User',
            'email' => 'no-domain@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ])->assertSessionHasErrors('email');

        $this->assertDatabaseCount('users', 0);
    }

    public function test_member_can_log_in_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'login@mhs.unesa.ac.id',
            'password' => Hash::make('Password123!'),
        ]);

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'Password123!',
        ])->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_is_redirected_to_admin_panel_after_login(): void
    {
        $admin = User::factory()->role(Role::ADMIN->value)->create([
            'email' => 'admin@mhs.unesa.ac.id',
            'password' => Hash::make('Password123!'),
        ]);

        $this->post(route('login.store'), [
            'email' => $admin->email,
            'password' => 'Password123!',
        ])->assertRedirect(route('admin.report.index'));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_login_rejects_incorrect_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'login-fail@mhs.unesa.ac.id',
            'password' => Hash::make('Password123!'),
        ]);

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'incorrect',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_authenticated_user_can_log_out(): void
    {
        $this->actingAsMember();

        $this->post(route('auth.logout'))
            ->assertRedirect(route('login.create'));

        $this->assertGuest();
    }
}
