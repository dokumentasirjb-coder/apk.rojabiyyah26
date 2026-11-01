<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string', // can be email or member_no
            'password' => 'required|string',
        ]);

        $login = $request->input('login');

        // Check if user exists by email, username prefix, or member_no
        $user = User::where('email', $login)
            ->orWhere('email', $login . '@rojabiyyah.id')
            ->orWhere('name', 'like', $login . '%')
            ->orWhere('member_no', strtoupper($login))
            ->orWhere('member_no', $login)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['Kredensial login (Email / Nomor Anggota dan Password) tidak valid.'],
            ]);
        }

        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'login' => ['Akun Anda telah dinonaktifkan oleh administrator.'],
            ]);
        }

        // Generate Sanctum token
        $user->tokens()->delete(); // single session / token refresh
        $token = $user->createToken('auth-token')->plainTextToken;

        $user->update(['last_login_at' => now()]);

        AuditLog::log('login', 'User', $user->id, null, ['ip' => $request->ip()]);

        return response()->json([
            'message' => 'Login berhasil.',
            'token' => $token,
            'user' => $user->load('member.division'),
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $user->tokens()->delete();
            AuditLog::log('logout', 'User', $user->id);
        }

        return response()->json([
            'message' => 'Logout berhasil.',
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user()->load('member.division');

        return response()->json([
            'user' => $user,
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Kata sandi lama tidak sesuai.'],
            ]);
        }

        $user->password = Hash::make($request->new_password);
        $user->must_change_password = false;
        $user->save();

        AuditLog::log('change_password', 'User', $user->id);

        return response()->json([
            'message' => 'Kata sandi berhasil diperbarui.',
            'user' => $user->load('member.division'),
        ]);
    }
}

