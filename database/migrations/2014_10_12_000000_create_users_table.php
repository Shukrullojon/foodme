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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken()->nullable();
            $table->string('name')->nullable();
            $table->string('chat_id')->nullable();
            $table->string('phone',20)->nullable();
            $table->string('first_name',50)->nullable();
            $table->string('last_name',50)->nullable();
            $table->string('username',50)->nullable();
            $table->double("balance")->default(0);
            $table->tinyInteger('step')->default(0)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
