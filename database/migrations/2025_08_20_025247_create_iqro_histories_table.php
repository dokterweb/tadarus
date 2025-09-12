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
        Schema::create('iqro_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->date('tgl_iqro');
            $table->unsignedInteger('iqro_id');
            $table->foreign('iqro_id')->references('id')->on('iqros')->onDelete('cascade');
            $table->string('iqro_jilid');
            $table->unsignedInteger('halaman');
            $table->unsignedInteger('nilai');
            $table->string('keterangan')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iqro_histories');
    }
};
