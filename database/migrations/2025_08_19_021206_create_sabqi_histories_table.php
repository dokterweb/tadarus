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
        Schema::create('sabqi_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sabqi_id');
            $table->foreign('sabqi_id')->references('id')->onDelete('cascade');
            $table->Integer('surat_id');
            $table->Integer('surat_no');
            $table->Integer('dariayat');
            $table->Integer('sampaiayat');
            $table->date('tgl_sabqi');
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
        Schema::dropIfExists('sabqi_histories');
    }
};
