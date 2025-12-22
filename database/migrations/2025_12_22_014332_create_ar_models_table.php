<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArSessionsTable extends Migration
{
    public function up(): void
    {
        Schema::create('ar_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('ar_model_id')->constrained('ar_models')->cascadeOnDelete();
            $table->string('device_type')->nullable(); // mobile/desktop/AR glasses
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->json('session_data')->nullable(); // lưu thông tin tracking nếu cần
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ar_sessions');
    }
}
