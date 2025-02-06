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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users'); // Foydalanuvchi
            $table->string('payment_type'); // To'lov turi (naqt/karta)
            $table->decimal('total_price', 10, 2); // Umumiy narx
            $table->decimal('discounted_price', 10, 2)->nullable(); // Chegirma (agar karta orqali bo'lsa)
            $table->string('status')->default('pending'); // Buyurtma holati
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
