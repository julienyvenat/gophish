<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_client_management()
    {
        $client = Client::factory()->create();
        $admin = User::factory()->create([
            'client_id' => $client->id,
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get(route('clients.index'));

        $response->assertStatus(200);
    }

    public function test_regular_user_cannot_access_client_management()
    {
        $client = Client::factory()->create();
        $user = User::factory()->create([
            'client_id' => $client->id,
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)->get(route('clients.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_create_client()
    {
        $client = Client::factory()->create();
        $admin = User::factory()->create([
            'client_id' => $client->id,
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->post(route('clients.store'), [
            'name' => 'New Corp',
            'domain' => 'newcorp.com',
        ]);

        $response->assertRedirect(route('clients.index'));
        $this->assertDatabaseHas('clients', [
            'name' => 'New Corp',
            'domain' => 'newcorp.com',
        ]);
    }
}
