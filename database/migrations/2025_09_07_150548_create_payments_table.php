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
            $table->increments('id');
            $table->enum('payment_type', ['bulan', 'bebas','buku']); // Kolom ENUM
            $table->unsignedInteger('periode_id');
            $table->foreign('periode_id')->references('id')->on('periodes')->onDelete('cascade');
            $table->unsignedInteger('posnya_id');
            $table->foreign('posnya_id')->references('id')->on('posnyas')->onDelete('cascade');
            $table->softDeletes();
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
