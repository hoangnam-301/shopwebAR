<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    // REGISTER
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed', // password_confirmation
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Gán role mặc định
        $role = Role::firstOrCreate(['name' => 'user']);
        $user->assignRole($role);

        $token = $user->createToken('api-token', [$role->name])->plainTextToken;

        return response()->json([
            'message' => 'Register success',
            'token'   => $token,
            'role'    => $role->name,
            'user'    => $user->only(['id', 'name', 'email']),
        ], 201);
    }

    // LOGIN
    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Email hoặc mật khẩu sai'], 401);
        }

        // Xóa token cũ
        $user->tokens()->delete();

        $roleName = $user->roles->first()?->name ?? 'user';
        $token = $user->createToken('api-token', [$roleName])->plainTextToken;

        return response()->json([
            'message' => 'Login success',
            'token'   => $token,
            'role'    => $roleName,
            'user'    => $user->only(['id', 'name', 'email'])
        ]);
    }

    // LOGOUT 1 TOKEN
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout success']);
    }

    // LOGOUT ALL TOKENS
    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logout all devices success']);
    }
}
