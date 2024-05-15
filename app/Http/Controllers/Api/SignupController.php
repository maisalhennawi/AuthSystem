<?php

namespace App\Http\Controllers\Api;
use App\Events\EmailVerificationEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profil;

class SignupController extends Controller
{
    public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'phone_number' => 'required|string|max:255',
        'password' => 'required|string|confirmed|min:8',
        'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'certificate' => 'required|file|mimes:pdf|max:10240',
    ]);

    $user = User::create([
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'phone_number' => $validatedData['phone_number'],
        'password' => Hash::make($validatedData['password']),
    ]);
    $request->validate([
        'password' => ['required', 'confirmed', 'min:8'],
    ], [
        'password.confirmed' => 'The password confirmation does not match.',
    ]);
    if ($request->hasFile('profile_photo')) {
        $profilePhotoPath = $request->file('profile_photo')->store('profile_photos', 'public');
        $user->profile_photo_path = $profilePhotoPath;
        $user->save();
    }

    if ($request->hasFile('certificate')) {
        $certificatePath = $request->file('certificate')->store('certificates', 'public');
        $user->certificate_path = $certificatePath;
        $user->save();
    }

    event(new EmailVerificationEvent($user));

    return redirect()->route('login');
}
}
