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
        Schema::create('fproducts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('info')->nullable();
            $table->float('price');
            $table->float('old_price');
            $table->float('come_price');
            $table->string("image")->nullable();
            $table->tinyInteger("status")->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fproducts');
    }
};
