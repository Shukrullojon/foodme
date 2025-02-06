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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ovqat nomi
            $table->text('info')->nullable(); // Qo‘shimcha ma’lumot
            $table->decimal('price', 10, 2); // Narx
            $table->boolean('status')->default(1); // 1 = mavjud, 0 = mavjud emas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
