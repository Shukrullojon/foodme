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
        Schema::create('game_players', function (Blueprint $table) {
            $table->id();
            $table->string('game_id');
            $table->string('chat_id');
            $table->tinyInteger('status')->default(0)->comment("0 -> waiting, 1 -> playing, 4 -> finished");
            $table->unsignedBigInteger('circle_id')->nullable();
            $table->double('month')->nullable();
            $table->tinyInteger('childs')->nullable();
            $table->double('money')->nullable();
            $table->unsignedBigInteger('purpose_money')->nullable();
            $table->tinyInteger('random')->default(0);
            $table->unsignedBigInteger('circle_card_id')->nullable()->comment("user qaysi kartaga kelib qolgani!");
            $table->unsignedBigInteger('work_id')->nullable();
            $table->unsignedBigInteger('purpose_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_players');
    }
};
