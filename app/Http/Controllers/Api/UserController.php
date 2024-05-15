<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profil;

class UserController extends Controller
{
    public function logout()
{
    auth()->user()->tokens()->delete();

    return response()->json([
        'status' => 'success',
        'message' => 'User is logged out successfully'
    ], 200);
}

public function refresh()
{
    $token = auth()->refresh();

    return response()->json([
        'status' => 'success',
        'token' => $token
    ], 200);
}
}
