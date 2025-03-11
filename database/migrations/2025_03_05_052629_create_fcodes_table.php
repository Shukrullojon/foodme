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
        Schema::create('fcodes', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->string("code")->nullable();
            $table->integer("amount")->default(0);
            $table->tinyInteger("status")->default(1)->comment("1->active, 4->tugadi");
            $table->tinyInteger("times")->default(1);
            $table->tinyInteger("used_times")->default(0);
            $table->string("used_chat_id")->nullable();
            $table->timestamp("expires_at")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fcodes');
    }
};
