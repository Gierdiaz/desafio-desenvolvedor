<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, Log};
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(AuthRequest $request)
    {
        $validatedData = $request->validated();

        try {
            $user = User::create([
                'name'     => $validatedData['name'],
                'email'    => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            Log::info('===================== User registered successfully.', ['user_id' => $user->id]);

            return response()->json(['message' => 'User registered successfully'], 201);
        } catch (\Exception $e) {
            Log::error(['error' => $e->getMessage()]);

            return response()->json(['error' => 'Error registering user.'], 500);
        }
    }

    public function login(AuthRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::where('email', $validatedData['email'])->first();

        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            Log::warning('Login failed. Incorrect credentials.', ['email' => $request->email]);

            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        Log::info('User authenticated successfully.', ['user_id' => $user->id]);

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken();

        if ($token) {
            $token->delete();
            Log::info('User logged out successfully.', ['user_id' => $request->user()->id]);
        } else {
            Log::warning('Attempt to log out without a valid token.', ['user_id' => $request->user()->id ?? null]);
        }

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
