<?php

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Hash;

it('can list vehicles publicly', function () {
    Vehicle::factory(5)->create();

    $response = $this->getJson('/api/vehicles');

    $response->assertStatus(200)
        ->assertJsonStructure(['success', 'data', 'meta']);
});

it('can filter vehicles by type', function () {
    Vehicle::factory(3)->create(['type' => 'car']);
    Vehicle::factory(2)->create(['type' => 'motorcycle']);

    $response = $this->getJson('/api/vehicles?type=car');

    $response->assertStatus(200);
    expect($response->json('data'))->toHaveCount(3);
});

it('can filter vehicles by max_price', function () {
    Vehicle::factory()->create(['price' => 50000]);
    Vehicle::factory()->create(['price' => 150000]);

    $response = $this->getJson('/api/vehicles?max_price=100000');

    $response->assertStatus(200);
    expect(collect($response->json('data'))->every(fn ($v) => $v['price'] <= 100000))->toBeTrue();
});

it('can show a vehicle', function () {
    $vehicle = Vehicle::factory()->create();

    $response = $this->getJson("/api/vehicles/{$vehicle->id}");

    $response->assertStatus(200)
        ->assertJson(['success' => true, 'data' => ['id' => $vehicle->id]]);
});

it('can create a vehicle when authenticated', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    $response = $this->actingAs($user, 'sanctum')->postJson('/api/vehicles', [
        'type' => 'car',
        'model' => 'Civic',
        'year' => 2024,
        'price' => 150000,
        'color' => 'Prata',
        'mileage' => 0,
    ]);

    $response->assertStatus(201)
        ->assertJson(['success' => true, 'data' => ['model' => 'Civic']]);
});

it('cannot create a vehicle when unauthenticated', function () {
    $response = $this->postJson('/api/vehicles', [
        'type' => 'car',
        'model' => 'Civic',
        'year' => 2024,
        'price' => 150000,
        'color' => 'Prata',
        'mileage' => 0,
    ]);

    $response->assertStatus(401);
});

it('validates vehicle creation', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    $response = $this->actingAs($user, 'sanctum')->postJson('/api/vehicles', [
        'type' => 'invalid',
        'model' => '',
        'year' => 1999,
        'price' => -1,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['type', 'model', 'year', 'price', 'color', 'mileage']);
});

it('can update a vehicle when authenticated', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);
    $vehicle = Vehicle::factory()->create();

    $response = $this->actingAs($user, 'sanctum')->putJson("/api/vehicles/{$vehicle->id}", [
        'model' => 'Updated Model',
    ]);

    $response->assertStatus(200)
        ->assertJson(['success' => true, 'data' => ['model' => 'Updated Model']]);
});

it('can delete a vehicle when authenticated', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);
    $vehicle = Vehicle::factory()->create();

    $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/vehicles/{$vehicle->id}");

    $response->assertStatus(204);
    $this->assertDatabaseMissing('vehicles', ['id' => $vehicle->id]);
});
