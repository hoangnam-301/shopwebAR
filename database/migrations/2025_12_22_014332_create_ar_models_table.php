<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArModelsTable extends Migration
{
    public function up(): void
    {
        Schema::create('ar_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('model_path'); // Đường dẫn file 3D
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ar_models');
    }
}
