<?php

use App\Models\Lead;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Hash;

it('can view dashboard when authenticated', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    $vehicle = Vehicle::factory()->create(['brand' => 'Honda', 'model' => 'Civic']);
    Lead::factory(5)->create(['vehicle_id' => $vehicle->id]);

    $response = $this->actingAs($user, 'sanctum')->getJson('/api/dashboard');

    $response->assertStatus(200)
        ->assertJsonStructure(['success', 'data' => ['total_vehicles', 'total_leads', 'most_requested_vehicle']]);
});

it('cannot view dashboard when unauthenticated', function () {
    $response = $this->getJson('/api/dashboard');

    $response->assertStatus(401);
});
