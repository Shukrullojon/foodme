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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('model');
            $table->unsignedBigInteger("invoice_id");
            $table->string("invoice_uuid")->nullable();
            $table->integer("store_id")->nullable();
            $table->double("amount")->nullable();
            $table->string("uuid")->nullable();
            $table->string("billing_id")->nullable();
            $table->date("payment_time")->nullable();
            $table->string('checkout_url')->nullable();
            $table->string("sign")->nullable();
            $table->json("response")->nullable();
            $table->tinyInteger("status")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
