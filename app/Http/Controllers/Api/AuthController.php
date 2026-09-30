<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Handle user login and issue Sanctum token.
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Kredensial yang diberikan tidak cocok dengan data kami.'],
            ]);
        }

        $token = $user->createToken('digipus-auth-token')->plainTextToken;
        $roles = $user->getRoleNames();

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'nim_nidn' => $user->nim_nidn,
                'faculty' => $user->faculty,
                'major' => $user->major,
                'max_borrow_quota' => $user->max_borrow_quota,
                'current_borrowed' => $user->activeLoans()->count(),
                'roles' => $roles,
            ],
        ]);
    }

    /**
     * Get current authenticated user profile.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        $roles = $user->getRoleNames();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'nim_nidn' => $user->nim_nidn,
            'faculty' => $user->faculty,
            'major' => $user->major,
            'max_borrow_quota' => $user->max_borrow_quota,
            'current_borrowed' => $user->activeLoans()->count(),
            'roles' => $roles,
        ]);
    }

    /**
     * Logout and revoke Sanctum token.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil. Token telah dicabut.',
        ]);
    }
}
