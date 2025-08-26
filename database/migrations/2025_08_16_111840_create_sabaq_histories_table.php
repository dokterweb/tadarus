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
        Schema::create('sabaq_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sabaq_id');
            $table->foreign('sabaq_id')->references('id')->onDelete('cascade');
            $table->Integer('surat_id');
            $table->Integer('surat_no');
            $table->Integer('dariayat');
            $table->Integer('sampaiayat');
            $table->date('tgl_sabaq');
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
        Schema::dropIfExists('sabaq_histories');
    }
};
