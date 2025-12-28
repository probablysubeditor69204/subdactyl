<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\PterodactylService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request, PterodactylService $ptero)
    {
        $request->validate([
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
        ]);

        // Create user on Pterodactyl
        try {
            $pteroUser = $ptero->createUser(
                $request->email,
                $request->username,
                $request->first_name,
                $request->last_name
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create Pterodactyl user: ' . $e->getMessage()], 500);
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'pterodactyl_user_id' => $pteroUser['id'],
            'role' => User::count() === 0 ? 'admin' : 'user', // First user is admin
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function me(Request $request)
    {
        return $request->user();
    }
}
