<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * BƯỚC 1: Nhập email → trả về reset_token
     */
    public function requestReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => hash('sha256', $token),
                'expires_at' => now()->addMinutes(10),
                'created_at' => now(),
            ]
        );

        return response()->json([
            'reset_token' => $token,
            'message' => 'Token hợp lệ trong 10 phút'
        ]);
    }

    /**
     * BƯỚC 2: Có token → mới được gửi OTP
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'reset_token' => 'required'
        ]);

        $tokenRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (
            !$tokenRecord ||
            $tokenRecord->expires_at < now() ||
            hash('sha256', $request->reset_token) !== $tokenRecord->token
        ) {
            return response()->json(['message' => 'Token không hợp lệ'], 400);
        }

        $otp = random_int(100000, 999999);

        DB::table('password_otps')->updateOrInsert(
            ['email' => $request->email],
            [
                'otp' => Hash::make($otp),
                'expires_at' => now()->addMinutes(5),
                'created_at' => now(),
            ]
        );

        Mail::raw("OTP đổi mật khẩu của bạn: $otp", function ($m) use ($request) {
            $m->to($request->email)->subject('OTP đổi mật khẩu');
        });

        return response()->json([
            'message' => 'Đã gửi OTP'
        ]);
    }

    /**
     * BƯỚC 3: OTP đúng → reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        $otpRecord = DB::table('password_otps')
            ->where('email', $request->email)
            ->first();

        if (
            !$otpRecord ||
            $otpRecord->expires_at < now() ||
            !Hash::check($request->otp, $otpRecord->otp)
        ) {
            return response()->json(['message' => 'OTP không đúng hoặc hết hạn'], 400);
        }

        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        // dọn rác
        DB::table('password_otps')->where('email', $request->email)->delete();
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json([
            'message' => 'Đổi mật khẩu thành công'
        ]);
    }
}
