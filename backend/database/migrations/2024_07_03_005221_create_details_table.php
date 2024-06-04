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
        Schema::create('details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tID');
            $table->unsignedBigInteger('iID');
            $table->integer('quantity')->default(1);
            $table->float('subtotal');
            $table->timestamps();

            $table->foreign('tID')->references('id')->on('transaksis')->onDelete('cascade');
            $table->foreign('iID')->references('id')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('details');
    }
};
