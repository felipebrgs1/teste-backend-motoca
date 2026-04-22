<?php

use App\Models\Lead;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Hash;

it('can create a lead publicly', function () {
    $vehicle = Vehicle::factory()->create();

    $response = $this->postJson('/api/leads', [
        'name' => 'João Silva',
        'email' => 'joao@teste.com',
        'phone' => '(11) 99999-9999',
        'vehicle_id' => $vehicle->id,
        'message' => 'Tenho interesse',
    ]);

    $response->assertStatus(201)
        ->assertJson(['name' => 'João Silva']);
});

it('validates lead creation', function () {
    $response = $this->postJson('/api/leads', [
        'name' => '',
        'email' => 'invalid-email',
        'phone' => '',
        'vehicle_id' => 99999,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'email', 'phone', 'vehicle_id']);
});

it('can list leads when authenticated', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);
    Lead::factory(5)->create();

    $response = $this->actingAs($user, 'sanctum')->getJson('/api/leads');

    $response->assertStatus(200)
        ->assertJsonStructure(['data', 'current_page']);
});

it('cannot list leads when unauthenticated', function () {
    $response = $this->getJson('/api/leads');

    $response->assertStatus(401);
});

it('can list leads by vehicle when authenticated', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);
    $vehicle = Vehicle::factory()->create();
    Lead::factory(3)->create(['vehicle_id' => $vehicle->id]);

    $response = $this->actingAs($user, 'sanctum')->getJson("/api/vehicles/{$vehicle->id}/leads");

    $response->assertStatus(200);
    expect($response->json('data'))->toHaveCount(3);
});
