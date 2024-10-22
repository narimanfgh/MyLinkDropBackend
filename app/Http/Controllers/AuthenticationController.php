<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class AuthenticationController extends Controller
{
    // User Registration
public function register(Request $request)
{   
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'gender' => 'nullable|in:male,female,other', // optional gender validation
        'birthday' => 'nullable|date',
        'language' => 'nullable|string|max:10',
        'bio' => 'nullable|string',
        'profilePicture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Image validation
        'affiliateID' => 'nullable|string',
    ]);
   // Handle image upload
$profilePicturePath = null;

if ($request->hasFile('profilePicture')) {
    // Check if the file is valid
    if ($request->file('profilePicture')->isValid()) {
        // Save the file in the storage/public directory
        $profilePicturePath = $request->file('profilePicture')->store('profile_pictures', 'public');
    
    }
}

    // Create the user
    $user = User::create([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'password' => Hash::make($request->input('password')),
        'gender' => $request->input('gender') ?? null,
        'birthday' => $request->input('birthday') ?? null,
        'language' => $request->input('language') ?? 'en',
        'bio' => $request->input('bio') ?? null,
        'profilePicture' => $profilePicturePath,  // Store the file path
        'affiliateID' => $request->input('affiliateID') ?? null,
    ]);

    return response()->json(['message' => 'User registered successfully'], 201);
}

    // User Login and Generate Token
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Generate a Sanctum API Token
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }

    
    // User Logout and Revoke Token
    public function logout(Request $request)
    {
        // Revoke the current token
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    // Get Authenticated User
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
