<?php

use App\Models\Company;
use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);
it('can create a position', function () {
    $positionData = [
        'title' => 'Software Engineer',
        'advertising_start_date' => now()->toDateString(),
        'advertising_end_date' => now()->addMonth()->toDateString(),
        'description' => 'Develop and maintain web applications.',
        'company_id' => Company::factory()->create()->id,
        'user_id' => User::factory()->create()->id,
        'position_type' => 'permanent',
    ];

    $response = $this->post('/positions', $positionData);

    $response->assertStatus(201); // Or appropriate status code
    $this->assertDatabaseHas('positions', ['title' => 'Software Engineer']);
});

it('can update a position', function () {
    $position = Position::factory()->create();

    $updateData = [
        'title' => 'Senior Software Engineer',
        'min_salary' => 90000,
        'max_salary' => 120000,
    ];

    $response = $this->put("/positions/{$position->id}", $updateData);

    $response->assertStatus(200);
    $this->assertDatabaseHas('positions', ['title' => 'Senior Software Engineer', 'min_salary' => 90000]);
});

it('can delete a position', function () {
    $user = User::factory()->create();
    $user->assignRole('administrator'); // Ensure the user has the correct role

    $this->actingAs($user);

    $position = Position::factory()->create(); // Create a position

    $response = $this->delete("/api/positions/{$position->id}");

    $response->assertStatus(204); // Assert for No Content on delete
    $this->assertDatabaseMissing('positions', ['id' => $position->id]); // Check it was deleted
});
