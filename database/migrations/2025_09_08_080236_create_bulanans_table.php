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
        Schema::create('bulanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('payment_id');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->unsignedInteger('bulan_id');
            $table->foreign('bulan_id')->references('id')->on('bulans')->onDelete('cascade');
            $table->unsignedInteger('bulan_bill');
            $table->enum('bulan_status', ['0', '1'])->default('0'); // Kolom ENUM
            $table->unsignedInteger('bulan_number_pay');
            $table->date('bulan_date_pay');
            $table->string('bukti_bulan');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulanans');
    }
};
