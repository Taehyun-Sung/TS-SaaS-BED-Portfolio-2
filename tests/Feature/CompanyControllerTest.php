<?php

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Use the RefreshDatabase trait to reset the database state for each test
    $this->useRefreshDatabase();
});

it('can get companies', function () {
    // Create a user with appropriate role/permissions
    $user = User::factory()->create();
    $user->assignRole('staff'); // Assign the role needed for accessing companies

    // Act as the authenticated user
    $this->actingAs($user);

    // Optionally create a company
    Company::factory()->create();

    // Make the API request
    $response = $this->get('/api/companies');

    // Assert the response
    $response->assertStatus(200);
    $response->assertJsonStructure([
        '*' => ['id', 'name', 'created_at', 'updated_at'], // Adjust the structure as needed
    ]);
});

it('can create a company', function () {
    $user = User::factory()->create();
    $user->assignRole('administrator'); // Role with permission to add companies

    $this->actingAs($user);

    $companyData = [
        'name' => 'New Company',
        // Add other required fields here
    ];

    $response = $this->post('/api/companies', $companyData);

    $response->assertStatus(201); // Assert created status
    $this->assertDatabaseHas('companies', $companyData); // Check the company was added to the database
});

it('can update a company', function () {
    $user = User::factory()->create();
    $user->assignRole('administrator');

    $this->actingAs($user);

    $company = Company::factory()->create();

    $updatedData = [
        'name' => 'Updated Company',
        // Add other fields to update here
    ];

    $response = $this->put("/api/companies/{$company->id}", $updatedData);

    $response->assertStatus(200); // Assert successful update
    $this->assertDatabaseHas('companies', $updatedData); // Check the company was updated
});

it('can delete a company', function () {
    $user = User::factory()->create();
    $user->assignRole('administrator');

    $this->actingAs($user);

    $company = Company::factory()->create();

    $response = $this->delete("/api/companies/{$company->id}");

    $response->assertStatus(204); // Assert no content (deleted)
    $this->assertDatabaseMissing('companies', ['id' => $company->id]); // Check the company was deleted
});
