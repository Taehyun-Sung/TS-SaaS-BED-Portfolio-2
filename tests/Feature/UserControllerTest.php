<?php

use App\Models\Company;
use App\Models\User;


it('has user route', function () {
    $user = User::factory()->create([
        'user_type' => 'staff'
    ]);
    $this->actingAs($user, 'sanctum');
    $response = $this->getJson('/api/v1/users');
    $response->assertStatus(200);
});


it('can browse users', function () {
    User::factory()->count(5)->create([]);
    $user = User::factory()->create([
        'user_type' => 'administrator'
    ]);
    $this->actingAs($user, 'sanctum');
    $response = $this->getJson('/api/v1/users');
    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => [
                    'id', 'nickname', 'given_name', 'family_name', 'email',
                    'company_id', 'user_type', 'status', 'updated_at', 'created_at']
            ]
        ]);
});

it('can retrieve a single user', function() {
    $user = User::factory()->create([
        'user_type' => 'administrator'
    ]);
    $this->actingAs($user, 'sanctum');
    $response = $this->getJson("/api/v1/users/{$user->id}");
    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'User retrieved successfully',
            'data' => [
                'id' => $user->id,
                'nickname' => $user->nickname,
                'given_name' => $user->given_name,
                'family_name' => $user->family_name,
                'email' => $user->email,
                'company_id' => $user->company_id,
                'user_type' => $user->user_type,
                'status' => $user->status,
                'created_at' => $user->created_at->toJson(),
                'updated_at' => $user->updated_at->toJson(),
                'deleted_at' => null,
            ]
        ]);
});

it('can create a user', function() {
    $company = Company::factory()->create();
    $user = User::factory()->create([
        'user_type' => 'staff'
    ]);
    $this->actingAs($user, 'sanctum');
    $response = $this->postJson('/api/v1/users', [
        'id' => 1,
        'nickname' => 'Tom',
        'given_name' => 'John',
        'family_name' => 'Smith',
        'email' => 'john@test.com',
        'password' => 'Password1',
        'company_id' => $company->id,
        'user_type' => 'client',
        'status' => 'active',
    ]);
    $response->assertStatus(201);
    $response->assertJson([
        'success' => true,
        'message' => 'User created successfully',
        'data' => [
            'nickname' => 'Tom',
            'given_name' => 'John',
            'family_name' => 'Smith',
            'email' => 'john@test.com',
            'company_id' => $company->id,
            'user_type' => 'client',
            'status' => 'active',
        ]
    ]);
});

it('can update a user', function () {
    $company = Company::factory()->create();
    $targetUser = User::create([
        'nickname' => 'Tom',
        'given_name' => 'John',
        'family_name' => 'Smith',
        'email' => 'john@test.com',
        'password' => 'Password1',
        'company_id' => $company->id,
        'user_type' => 'client',
        'status' => 'active',
    ]);
    $user = User::factory()->create([
        'user_type' => 'staff'
    ]);
    $user = User::factory()->create([
        'user_type' => 'staff'
    ]);
    $this->actingAs($user, 'sanctum');
    $response = $this->putJson("api/v1/users/{$targetUser->id}", [
        'nickname' => 'Tom',
        'given_name' => 'Bob',
        'family_name' => 'Smith',
        'email' => 'bob@test.com',
        'password' => bcrypt('Password1'),
        'company_id' => $company->id,
        'user_type' => 'client',
        'status' => 'active',
    ]);
//    dd($response);
    $response->assertStatus(200);
    expect($response->json('data'))
        ->nickname->toBe('Tom')
        ->given_name->toBe('Bob')
        ->family_name->toBe('Smith')
        ->email->toBe('bob@test.com')
        ->company_id->toBe($company->id)
        ->user_type->toBe('client')
        ->status->toBe('active');
});

it('can delete a user', function () {
    $targetUser = User::factory()->create();
    $user = User::factory()->create([
        'user_type' => 'administrator'
    ]);
    $this->actingAs($user, 'sanctum');
    $response = $this->deletejson("api/v1/users/{$targetUser->id}");
    $response->assertStatus(200);
    $this->assertSoftDeleted('users', ['id' => $targetUser->id]);
    $response= $this->getjson("api/v1/users/{$targetUser->id}");
    $response->assertStatus(404);
});

it('can restore a soft-deleted user', function () {
    $targetUser = User::factory()->create();
    $user = User::factory()->create([
        'user_type' => 'administrator'
    ]);
    $this->actingAs($user, 'sanctum');
    $targetUser->delete();
    $this->assertSoftDeleted($targetUser);

    $response = $this->patchjson("api/v1/users/{$targetUser->id}/restore");
    $response->assertStatus(200);

    $restoredUser = User::find($targetUser->id);
    $this->assertNotSoftDeleted($restoredUser);
    expect($restoredUser)
        ->name->toBe($user->name);
});
