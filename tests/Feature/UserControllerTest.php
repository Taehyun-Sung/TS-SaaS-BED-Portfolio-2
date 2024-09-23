<?php

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('can create a user', function () {
    $company = Company::factory()->create();
    $userData = [
        'nickname' => 'johnny',
        'given_name' => 'John',
        'family_name' => 'Doe',
        'email' => 'john.doe@example.com',
        'password' => 'password123',
        'company_id' => $company->id,
        'user_type' => 'client',
        'status' => 'active',
    ];

    $response = $this->post('/users', $userData);

    $response->assertStatus(201); // Or appropriate status code
    $this->assertDatabaseHas('users', ['email' => 'john.doe@example.com']);
});

it('can associate a user with a company', function () {
    $company = Company::factory()->create();
    $user = User::factory()->create(['company_id' => $company->id]);

    $this->assertEquals($company->id, $user->company->id);
});

it('can update a user', function () {
    $user = User::factory()->create();

    $updateData = [
        'given_name' => 'Jane',
        'family_name' => 'Doe',
        'status' => 'suspended',
    ];

    $response = $this->put("/users/{$user->id}", $updateData);

    $response->assertStatus(200);
    $this->assertDatabaseHas('users', ['given_name' => 'Jane', 'status' => 'suspended']);
});

it('can delete a user', function () {
    $user = User::factory()->create();

    $response = $this->delete("/users/{$user->id}");

    $response->assertStatus(200);
    $this->assertSoftDeleted('users', ['id' => $user->id]);
});
