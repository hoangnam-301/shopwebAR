<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained('carts')->cascadeOnDelete();
            $table->string('payment_method'); // ví dụ: 'card', 'paypal', 'momo'...
            $table->decimal('amount', 10, 2); // số tiền thanh toán
            $table->string('status')->default('pending'); // pending, success, failed
            $table->timestamp('paid_at')->nullable(); // thời gian thanh toán
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
}
