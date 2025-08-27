<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\TravelRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TravelRequestTest extends TestCase
{
    use RefreshDatabase;

    private function authenticate(User $user)
    {
        $token = auth()->login($user);
        return ["Authorization" => "Bearer $token"];
    }

    /** @test */
    public function authenticated_user_can_create_travel_request()
    {
        $user = User::factory()->create();
        $headers = $this->authenticate($user);

        $response = $this->postJson('/api/travel-requests', [
            'destino' => 'São Paulo',
            'data_ida' => '2025-09-01',
            'data_volta' => '2025-09-10',
        ], $headers);

        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'destino', 'status']);
    }

    /** @test */
    public function guest_cannot_create_travel_request()
    {
        $response = $this->postJson('/api/travel-requests', [
            'destino' => 'São Paulo',
            'data_ida' => '2025-09-01',
            'data_volta' => '2025-09-10',
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function user_can_list_only_their_own_travel_requests()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        TravelRequest::factory()->create(['user_id' => $user1->id, 'destino' => 'Rio']);
        TravelRequest::factory()->create(['user_id' => $user2->id, 'destino' => 'Paris']);

        $headers = $this->authenticate($user1);
        $response = $this->getJson('/api/travel-requests', $headers);

        $response->assertStatus(200)
                 ->assertJsonCount(1)
                 ->assertJsonFragment(['destino' => 'Rio']);
    }

    /** @test */
    public function admin_can_update_status()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();

        $request = TravelRequest::factory()->create(['user_id' => $user->id]);

        $headers = $this->authenticate($admin);
        $response = $this->patchJson("/api/travel-requests/{$request->id}/status", [
            'status' => 'aprovado'
        ], $headers);

        $response->assertStatus(200)
                 ->assertJsonFragment(['status' => 'aprovado']);
    }

    /** @test */
    public function normal_user_cannot_update_status()
    {
        $user = User::factory()->create();
        $request = TravelRequest::factory()->create(['user_id' => $user->id]);

        $headers = $this->authenticate($user);
        $response = $this->patchJson("/api/travel-requests/{$request->id}/status", [
            'status' => 'aprovado'
        ], $headers);

        $response->assertStatus(403);
    }

    /** @test */
    public function approved_request_cannot_be_cancelled()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();

        $request = TravelRequest::factory()->create([
            'user_id' => $user->id,
            'status' => 'aprovado'
        ]);

        $headers = $this->authenticate($admin);
        $response = $this->patchJson("/api/travel-requests/{$request->id}/status", [
            'status' => 'cancelado'
        ], $headers);

        $response->assertStatus(422);
    }
}
