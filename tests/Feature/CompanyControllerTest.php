<?php

use App\Models\Company;
use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Spatie\PestPluginTestTime\testTime;


uses( RefreshDatabase::class);

it('has company route', function () {
    $user = User::factory()->create([
        'user_type' => 'staff',
    ]);

    $this->actingAs($user, 'sanctum')
        ->get('api/v1/companies')
        ->assertStatus(200);
});

it('has single company route', function (){
    $user = User::factory()->create();
    $company = Company::factory()->create();

    $this->actingAs($user, 'sanctum')
        ->getJson("/api/v1/companies/{$company->id}")
        ->assertStatus(200);
});


it('can browse companies', function () {
    Company::factory()->count(5)->create();

    $user = User::factory()->create();
    $this->actingAs($user, 'sanctum');

    $response = $this->getJson('/api/v1/companies');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => ['id', 'name', 'city_id', 'state_id', 'country_id', 'logo', 'created_at', 'updated_at', 'deleted_at']
            ]
        ]);
});

it('can retrieve single company', function (){
    $companies = Company::factory()->count(5)->create();
    $company = $companies->first();
    $id = $company->id;

    $user = User::factory()->create();
    $this->actingAs($user, 'sanctum');

    $response = $this->getJson("/api/v1/companies/{$id}");

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Company retrieved successfully',
            'data' => [
                'id' => $company->id,
                'name' => $company->name,
                'city_id' => $company->city_id,
                'state_id' => $company->state_id,
                'country_id' => $company->country_id,
                'logo' => $company->logo,
                'deleted_at' => null,
                'created_at' => $company->created_at->toJSON(),
                'updated_at' => $company->updated_at->toJSON(),
            ],
        ]);
});

it('can create a company', function () {
    $user = User::factory()->create();
    $this->actingAs($user, 'sanctum');

    $response = $this->postJson('/api/v1/companies', [
        'name' => 'Test Company',
        'city_id' => 1,
        'state_id' => 2,
        'country_id' => 3,
        'logo' => null,  // If logo is nullable, make sure it's set as null
    ]);

    $response->assertStatus(201);
    $response->assertJsonStructure([
        'success',
        'message',
        'data' => [
            'id',
            'name',
            'city_id',
            'state_id',
            'country_id',
            'logo',
            'created_at',
            'updated_at'
        ]
    ]);

    expect($response->json('data'))
        ->name->toBe('Test Company')
        ->city_id->toBe(1)
        ->state_id->toBe(2)
        ->country_id->toBe(3)
        ->logo->toBeNull();
});


it('can update a company', function (){
    $company = Company::factory()->create([
        'name' => 'Test Company',
        'city_id' => 1,
        'state_id' => 2,
        'country_id' => 3,
        'logo' => null,
    ]);

    $user = User::factory()->create([
        'company_id' => $company->id,
    ]);
    $this->actingAs($user, 'sanctum');

    $response = $this->putJson("/api/v1/companies/{$company->id}",[
        'name' => 'Updated Test Company',
        'city_id' => 1,
        'state_id' => 2,
        'country_id' => 3,
        'logo' => null
    ]);
    $response->assertStatus(200);
    expect($response->json('data'))
        ->id->toBe($company->id)
        ->name->toBe('Updated Test Company')
        ->city_id->toBe(1)
        ->state_id->toBe(2)
        ->country_id->toBe(3)
        ->logo->toBeNull();
});

it('can delete a company', function(){
    $company = Company::factory()->create();
    $user = User::factory()->create();
    $this->actingAs($user, 'sanctum');

    $response = $this->deleteJson("api/v1/companies/{$company->id}");
    $response->assertStatus(200);
    $this->assertSoftDeleted('companies', ['id' => $company->id]);
    $response = $this->getJson("/api/v1/companies/{$company->id}");
    $response->assertStatus(404);
});

it('can restore a soft-deleted company', function (){
    $company = Company::factory()->create();
    $user = User::factory()->create();
    $this->actingAs($user, 'sanctum');

    $company->delete();
    $this->assertSoftDeleted($company);

    $response = $this->patchJson("api/v1/companies/{$company->id}/restore");
    $response->assertStatus(200);

    $restoredCompany = Company::find($company->id);
    $this->assertNotSoftDeleted($restoredCompany);
    expect($restoredCompany)
        ->name->toBe($company->name);
});

it("can undo the soft deletes", function(){
    $company = Company::factory()->create();
    $user = User::factory()->create();
    $this->actingAs($user, 'sanctum');

    $company->delete();
    $this->assertNotNull($company->fresh()->deleted_at); //verify that deleted_at col is not null or empty
    $this->assertSoftDeleted('companies', ['id' => $company->id]);
    $company->restore();
    $this->assertNull($company->fresh()->deleted_at);
    $this->assertDatabaseHas('companies',['id' => $company->id]);
});

it('company can have multiple positions', function() {
    $company = Company::factory()->create();

    $positions = Position::factory()->count(5)->create([
        'company_id' => $company->id,
    ]);

    $companyWithPositions = Company::with('positions')->find($company->id);

    $this->assertCount(5, $companyWithPositions->positions);

    foreach ($companyWithPositions->positions as $position) {
        $this->assertEquals($company->id, $position->company_id);
    }
});
