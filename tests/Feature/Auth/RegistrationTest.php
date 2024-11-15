<?php

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'user_type' => 'staff',
        'status' => 'active',
        'given_name' => 'Test',  // Make sure given_name is included
        'family_name' => 'Test',
    ]);

    $response->assertStatus(201); // Assert 201 (created)
    $this->assertAuthenticated(); // Assert that the user is authenticated
});
