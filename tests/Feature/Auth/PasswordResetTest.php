<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;

test('reset password link can be requested', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->post('/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class);
});

test('password can be reset with valid token', function () {
    Notification::fake();

    $user = User::factory()->create();

    // Step 1: Trigger password reset email
    $this->post('/forgot-password', ['email' => $user->email]);

    // Step 2: Get the reset token from the notification
    Notification::assertSentTo($user, ResetPassword::class, function (object $notification) use ($user) {
        // Step 3: Attempt to reset the password
        $response = $this->post('/reset-password', [
            'token' => $notification->token,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // Step 4: Assert that the user is redirected to the correct route after password reset
        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/login'); // Adjust this to the actual route where the user should be redirected

        return true;
    });
});

