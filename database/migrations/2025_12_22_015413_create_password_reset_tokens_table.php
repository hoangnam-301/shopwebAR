<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordResetTokensTable extends Migration
{
    public function up(): void
    {
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->unique('email'); // mỗi email chỉ có 1 token tại 1 thời điểm
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
}
