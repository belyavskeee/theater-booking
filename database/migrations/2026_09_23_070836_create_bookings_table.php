<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('performance_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['pending', 'paid', 'cancelled', 'expired'])->default('pending');
            $table->decimal('total_price', 10, 2)->default(0);
            $table->dateTime('expires_at')->nullable();
            // контактные данные
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            // оплата
            $table->string('payment_method')->nullable(); // card, eriop, apple_pay, google_pay
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
