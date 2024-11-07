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
        Schema::create('baskets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->integer("count")->default(0);
            $table->double('price')->default(35000);
            $table->double('benefit_price')->default(5000);
            $table->string('chat_id')->nullable();
            $table->tinyInteger("status")->default(0)->comment("0->buyurtma qabul qilindi, 1->To'landi, 2->bekor qilindi, 4 -> Yetkazib berilgan");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('baskets');
    }
};
