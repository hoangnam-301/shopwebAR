<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    /**
     * ĐĂNG KÝ TÀI KHOẢN MỚI
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Gán role mặc định là user
        $role = Role::firstOrCreate(['name' => 'user']);
        $user->assignRole($role);

        // Tạo token với khả năng (abilities) dựa trên tên role
        $token = $user->createToken('api-token', [$role->name])->plainTextToken;

        return response()->json([
            'message' => 'Register success',
            'token'   => $token,
            'role'    => $role->name,
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'roles' => $user->roles // Trả về mảng roles để frontend nhận diện
            ],
        ], 201);
    }

    /**
     * ĐĂNG NHẬP
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Email hoặc mật khẩu không chính xác'], 401);
        }

        // Xóa các token cũ để tránh rác database (Tùy chọn)
        $user->tokens()->delete();

        // Lấy tên role đầu tiên của user, mặc định là user nếu chưa có
        $roleName = $user->getRoleNames()->first() ?? 'user';
        
        // Tạo token mới
        $token = $user->createToken('api-token', [$roleName])->plainTextToken;

        return response()->json([
            'message' => 'Login success',
            'token'   => $token,
            'role'    => $roleName, // Trả về chuỗi 'admin' hoặc 'user' trực tiếp
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'roles' => $user->roles // QUAN TRỌNG: Phải gửi mảng này về để Frontend kiểm tra
            ]
        ]);
    }

    /**
     * ĐĂNG XUẤT (Xóa token hiện tại)
     */
    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logout success']);
        }
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    /**
     * ĐĂNG XUẤT KHỎI TẤT CẢ THIẾT BỊ
     */
    public function logoutAll(Request $request)
    {
        if ($request->user()) {
            $request->user()->tokens()->delete();
            return response()->json(['message' => 'Logout all devices success']);
        }
        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
