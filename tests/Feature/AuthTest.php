<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    // -------------------------------------------------------------------------
    // Register page
    // -------------------------------------------------------------------------

    public function test_register_page_is_accessible(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    // -------------------------------------------------------------------------
    // User registration (store)
    // -------------------------------------------------------------------------

    public function test_user_can_register_with_valid_data(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Alice',
            'email'                 => 'alice@example.com',
            'password'              => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('users', ['email' => 'alice@example.com']);
    }

    public function test_register_requires_name(): void
    {
        $response = $this->post('/register', [
            'name'                  => '',
            'email'                 => 'alice@example.com',
            'password'              => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_register_requires_valid_email(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Alice',
            'email'                 => 'not-an-email',
            'password'              => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_register_requires_unique_email(): void
    {
        User::factory()->create(['email' => 'alice@example.com']);

        $response = $this->post('/register', [
            'name'                  => 'Alice2',
            'email'                 => 'alice@example.com',
            'password'              => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_register_requires_password_minimum_length(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Alice',
            'email'                 => 'alice@example.com',
            'password'              => 'ab',
            'password_confirmation' => 'ab',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_register_requires_password_confirmation(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Alice',
            'email'                 => 'alice@example.com',
            'password'              => 'secret123',
            'password_confirmation' => 'different',
        ]);

        $response->assertSessionHasErrors('password');
    }

    // -------------------------------------------------------------------------
    // Login page
    // -------------------------------------------------------------------------

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    // -------------------------------------------------------------------------
    // Authentication (authenticate)
    // -------------------------------------------------------------------------

    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'email'    => 'bob@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email'    => 'bob@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        User::factory()->create([
            'email'    => 'bob@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email'    => 'bob@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_fails_with_nonexistent_email(): void
    {
        $response = $this->post('/login', [
            'email'    => 'nobody@example.com',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_requires_email(): void
    {
        $response = $this->post('/login', [
            'email'    => '',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_login_requires_password(): void
    {
        $response = $this->post('/login', [
            'email'    => 'bob@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors('password');
    }

    // -------------------------------------------------------------------------
    // Logout
    // -------------------------------------------------------------------------

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
