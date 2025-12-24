<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordOtpsTable extends Migration
{
    public function up(): void
    {
        Schema::create('password_otps', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('otp'); // lưu hash của OTP
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->unique('email'); // mỗi email chỉ có 1 OTP tại 1 thời điểm
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_otps');
    }
}
