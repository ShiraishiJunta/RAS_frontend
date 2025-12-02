<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Register Organizer Baru
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:organizers',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $organizer = Organizer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $organizer->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi Berhasil',
            'data' => $organizer,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 201);
    }

    // Login Organizer
    public function login(Request $request)
    {
        // Validasi input dasar
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Cari organizer berdasarkan email
        $organizer = Organizer::where('email', $request->email)->first();

        // Cek apakah user ada dan password cocok
        if (!$organizer || !Hash::check($request->password, $organizer->password)) {
            return response()->json([
                'message' => 'Email atau Password salah'
            ], 401);
        }

        // Jika sukses, buat token
        $token = $organizer->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login Berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'data' => $organizer
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        // Menghapus token yang sedang digunakan saat ini
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout Berhasil'
        ]);
    }
}