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
        Schema::create('game_bilet', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_player_id');
            $table->unsignedBigInteger('bilet_id');
            $table->tinyInteger('status')->default(0)->comment("4 -> active, 21 -> close");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_bilet');
    }
};
