<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Send OTP
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:15',
        ]);

        $otp = rand(100000, 999999);

        $user = User::updateOrCreate(
            ['phone' => $request->phone],
            ['otp' => $otp]
        );

        return response()->json([
            'success' => true,
            'message' => 'OTP generated successfully',
            'otp' => $otp, // Testing ke liye
        ]);
    }

   // Verify OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:15',
            'otp' => 'required|string',
        ]);

        $user = User::where('phone', $request->phone)->where('otp', $request->otp)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP',
            ], 401);
        }

        $time = $user->updated_at;
        if(now()->diffInMinutes($time) > 5) {
            return response()->json([
                'success' => false,
                'message' => 'OTP expired',
            ], 401);
        }

        // Create Token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully',
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }
}