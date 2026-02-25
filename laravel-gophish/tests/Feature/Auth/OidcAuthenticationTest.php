<?php

namespace Tests\Feature\Auth;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;
use Mockery;

class OidcAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_via_oidc_and_be_assigned_to_client()
    {
        // Create a client with a domain
        Client::create(['name' => 'Acme Corp', 'domain' => 'acme.com']);

        // Mock Socialite User
        $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser->shouldReceive('getId')->andReturn(123456);
        $abstractUser->shouldReceive('getEmail')->andReturn('john.doe@acme.com');
        $abstractUser->shouldReceive('getName')->andReturn('John Doe');
        $abstractUser->shouldReceive('getAvatar')->andReturn('https://en.gravatar.com/userimage');

        // Mock Socialite Provider
        $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('oidc')->andReturn($provider);

        // Hit the callback route
        $response = $this->get('/login/oauth/oidc/callback');

        // Assert redirect to dashboard
        $response->assertRedirect('/dashboard');

        // Assert user created
        $this->assertDatabaseHas('users', [
            'email' => 'john.doe@acme.com',
            'name' => 'John Doe',
        ]);

        // Assert user assigned to client
        $user = User::where('email', 'john.doe@acme.com')->first();
        $this->assertNotNull($user->client_id);
        $this->assertEquals('Acme Corp', $user->client->name);
    }

    public function test_user_cannot_login_if_domain_not_registered()
    {
        // No client for 'unknown.com'

        // Mock Socialite User
        $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser->shouldReceive('getId')->andReturn(789012);
        $abstractUser->shouldReceive('getEmail')->andReturn('stranger@unknown.com');
        $abstractUser->shouldReceive('getName')->andReturn('Stranger');

        // Mock Socialite Provider
        $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('oidc')->andReturn($provider);

        // Hit the callback route
        $response = $this->get('/login/oauth/oidc/callback');

        // Assert redirect back to login with error
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email']);

        // Assert user NOT created
        $this->assertDatabaseMissing('users', [
            'email' => 'stranger@unknown.com',
        ]);
    }
}
