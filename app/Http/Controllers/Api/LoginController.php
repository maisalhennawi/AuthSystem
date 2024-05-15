<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profil;
class LoginController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'phone_number' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        $user = User::where('email', $request->email)->first();

    if ( !$user || ! Hash::check($request->password, $user->password)) {
        return response(['message' => 'The provided credentials are incorrect.'], 400);
    }
    


    $token = $user->createToken('auth_token', ['expires_at' => now()->addMinutes(config('sanctum.tokens.personal_access.expires_at'))])->plainTextToken;
    $refreshToken = $user->createToken('refresh_token', ['expires_at' => now()->addMinutes(config('sanctum.tokens.refresh.expires_at'))])->plainTextToken;

    return response(['token' => $token, 'refresh_token' => $refreshToken]);
}
//refresh the access token 
    public function update(Request $request)
{
    $user = $request->user();
    $user->tokens()->delete();
    $token = $user->createToken('auth_token')->plainTextToken;
    $refreshToken = $user->createToken('refresh_token')->plainTextToken;

    return response(['token' => $token, 'refresh_token' => $refreshToken]);
}
}