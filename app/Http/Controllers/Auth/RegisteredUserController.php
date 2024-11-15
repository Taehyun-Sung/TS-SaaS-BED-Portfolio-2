<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

/**
 * @group Authentication
 *
 * This controller handles the user registration process, including displaying the registration form and processing the registration request.
 * It creates a new user, fires the registration event, and logs the user in.
 *
 * @package App\Http\Controllers\Auth
 */
class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * This method renders the registration form view where users can input their details
     * to create a new account.
     *
     * @return View
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Registration view displayed successfully.",
     *   "data": {}
     * }
     *
     * @example Response:
     * {
     *   "success": true,
     *   "message": "Registration form loaded successfully.",
     *   "data": {}
     * }
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * This method processes the registration form submission. It validates the incoming data,
     * creates a new user in the database, fires a `Registered` event, and logs the user in automatically.
     *
     * @param Request $request The incoming HTTP request containing the registration data.
     *
     * @return RedirectResponse Redirects to the dashboard if registration is successful.
     *
     * @throws \Illuminate\Validation\ValidationException If the validation fails, it will throw an exception and return the validation errors.
     *
     * @response 201 {
     *   "success": true,
     *   "message": "User registered successfully.",
     *   "data": {
     *     "user": {
     *       "id": 1,
     *       "name": "Test User",
     *       "email": "test@example.com",
     *       "created_at": "2024-11-15T12:00:00Z",
     *       "updated_at": "2024-11-15T12:00:00Z"
     *     }
     *   }
     * }
     *
     * @example Response:
     * {
     *   "success": true,
     *   "message": "User registered successfully.",
     *   "data": {
     *     "user": {
     *       "id": 1,
     *       "name": "Test User",
     *       "email": "test@example.com",
     *       "created_at": "2024-11-15T12:00:00Z",
     *       "updated_at": "2024-11-15T12:00:00Z"
     *     }
     *   }
     * }
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
