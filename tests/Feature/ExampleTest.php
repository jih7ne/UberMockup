<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function registration_page_loads_correctly()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Register'); // checks if "Register" text is present
    }

    /** @test */
    public function login_page_loads_correctly()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Login'); // checks if "Login" text is present
    }

    /** @test */
    public function user_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/home'); // or wherever your app redirects after register
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com'
        ]);
    }

}
