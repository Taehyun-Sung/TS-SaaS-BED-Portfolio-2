<?php

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Position;

uses(RefreshDatabase::class);

it('has position route')
    ->get('api/v1/positions')
    ->assertStatus(200);

it('can browse positions', function() {
    Position::factory()->count(5)->create();

    $response = $this->getJson('/api/v1/positions');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => ['id', 'advertising_start_date', 'advertising_end_date',
                    'title', 'description', 'keywords', 'min_salary', 'max_salary',
                    'salary_currency','company_id', 'user_id', 'benefits', 'requirements',
                    'position_type', 'updated_at', 'created_at']
            ]
        ]);
});

it('can retrieve single position', function () {
    $position = Position::factory()->create();
    $user = User::factory()->create([
        'company_id' => $position->company_id,
    ]);
    $this->actingAs($user, 'sanctum');

    $response = $this->getJson("/api/v1/positions/{$position->id}");
    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Position retrieved successfully',
            'data' => [
                'advertising_start_date' => $position->advertising_start_date,
                'advertising_end_date' => $position->advertising_end_date,
                'title' => $position->title,
                'description' => $position->description,
                'keywords' => $position->keywords,
                'min_salary' => $position->min_salary,
                'max_salary' => $position->max_salary,
                'salary_currency' => $position->salary_currency,
                'company_id' => $position->company_id,
                'user_id' => $position->user_id,
                'benefits' => $position->benefits,
                'requirements' => $position->requirements,
                'position_type' => $position->position_type,
            ]
        ]);
});

it('can create a position', function() {
    $company = Company::factory()->create();

    $user = User::factory()->create([
        'company_id' => $company->id,
        'user_type' => 'staff',
    ]);
    $this->actingAs($user, 'sanctum');

    $response = $this->postJson('/api/v1/positions', [
        'advertising_start_date' => now()->toDateString(),
        'advertising_end_date' => now()->addMonth()->toDateString(),
        'title' => 'Test Position',
        'description' => 'Test Position Description',
        'keywords' => 'test, position',
        'min_salary' => 50000,
        'max_salary' => 100000,
        'salary_currency' => 'AUD',
        'company_id' => $company->id,
        'user_id' => $user->id,
        'benefits' => 'Test benefits',
        'requirements' => 'Test requirements',
        'position_type' => 'permanent'

    ]);
    $response->assertStatus(201);
});

it('can update a position', function() {
    $position = Position::factory()->create();
    $user = User::factory()->create([
        'user_type' => 'staff',
    ]);
    $this->actingAs($user, 'sanctum');

    $response = $this->putJson("api/v1/positions/{$position->id}", [
        'advertising_start_date' => now()->toDateString(),
        'advertising_end_date' => now()->addMonth()->toDateString(),
        'title' => 'Updated Position',
        'description' => 'Updated Description',
        'keywords' => ['updated, position'],
        'min_salary' => 50000,
        'max_salary' => 100000,
        'salary_currency' => 'AUD',
        'company_id' => $position->company_id,
        'user_id' => $position->user_id,
        'benefits' => 'Test benefits',
        'requirements' => 'Test requirements',
        'position_type' => 'permanent'
    ]);
    $response->assertStatus(200);
    expect($response->json('data'))
        ->title->toBe('Updated Position')
        ->description->toBe('Updated Description')
        ->keywords->toBe(['updated, position']);
});

it('can delete a position', function(){
    $position = Position::factory()->create();
    $user = User::factory()->create([
        'user_type' => 'staff',
    ]);
    $this->actingAs($user, 'sanctum');

    $response = $this->deleteJson("api/v1/positions/{$position->id}");
    $response->assertStatus(200);
    $this->assertSoftDeleted('positions', ['id' => $position->id]);
    $response = $this->getJson("/api/v1/positions/{$position->id}");
    $response->assertStatus(404);
});

it('can restore a soft-deleted position', function (){
    $position = Position::factory()->create();
    $user = User::factory()->create([
        'user_type' => 'staff',
    ]);
    $this->actingAs($user, 'sanctum');
    $position->delete();
    $this->assertSoftDeleted($position);

    $response = $this->patchJson("api/v1/positions/{$position->id}/restore");
    $response->assertStatus(200);

    $restoredPosition = Position::find($position->id);
    $this->assertNotSoftDeleted($restoredPosition);
    expect($restoredPosition)
        ->name->toBe($position->name);
});


it('position has a company and user', function() {
    $company = Company::factory()->create();
    $user = User::factory()->create();

    $position = Position::factory()->create([
        'company_id' => $company->id,
        'user_id' => $user->id
    ]);

    $this->assertEquals($company->id, $position->company->id);
    $this->assertEquals($user->id, $position->user->id);
});

