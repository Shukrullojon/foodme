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
            $table->unsignedBigInteger('package_id')->nullable();
            $table->unsignedBigInteger("user_id")->nullable();
            $table->unsignedBigInteger('product_id');
            $table->integer("count")->default(1);
            $table->double('price')->default(35000);
            $table->double('benefit_price')->default(5000);
            $table->tinyInteger("status")->default(0)->comment("0->buyurtma qabul qilindi, 1->To'landi, 2->bekor qilindi, 4 -> Yetkazib berilgan");
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
