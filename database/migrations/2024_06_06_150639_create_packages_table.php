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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger("group_id")->nullable();
            $table->string("message_id")->nullable();
            $table->date("date");
            $table->string("phone",30)->nullable();
            $table->string("longitude")->nullable();
            $table->string("latitude")->nullable();
            $table->tinyInteger("status")->default(0)->comment("0->buyurtma qabul qilindi, 1->To'landi, 2->bekor qilindi, 4 -> Yetkazib berilgan");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
