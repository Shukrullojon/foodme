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
        Schema::create('circle_card', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('circle_id');
            $table->unsignedBigInteger('card_id');
            $table->string("gif")->nullable();
            $table->tinyInteger('salary')->default(0);
            $table->tinyInteger('start')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('circle_card');
    }
};
