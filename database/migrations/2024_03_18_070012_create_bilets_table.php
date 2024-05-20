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
        Schema::create('bilets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('card_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('name')->nullable();
            $table->string('info')->nullable();
            $table->bigInteger('investment')->nullable();
            $table->bigInteger('income')->nullable();
            $table->bigInteger('additional')->nullable();
            $table->bigInteger("one_price")->nullable()->comment("Mavsumiy biznes uchun");
            $table->tinyInteger('risk_1')->nullable();
            $table->tinyInteger('risk_2')->nullable();
            $table->bigInteger('spend')->nullable()->comment("Xarajatlar uchun!");
            $table->bigInteger('price')->nullable()->comment("Hovli, yer larni narxini kiritish uchun!");
            $table->bigInteger('award')->nullable()->comment("Bolangiz yutib keldi!");
            $table->tinyInteger('is_child')->nullable()->default(0);
            $table->tinyInteger('is_own')->nullable()->default(0);
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bilets');
    }
};
