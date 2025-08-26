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
        Schema::create('manzil_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('manzil_id');
            $table->foreign('manzil_id')->references('id')->onDelete('cascade');
            $table->Integer('surat_id');
            $table->Integer('surat_no');
            $table->Integer('dariayat');
            $table->Integer('sampaiayat');
            $table->date('tgl_manzil');
            $table->Integer('nilai');
            $table->string('keterangan');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manzil_histories');
    }
};
