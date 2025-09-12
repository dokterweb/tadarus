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
            $table->foreign('sabqi_id')->references('id')->on('sabqis')->onDelete('cascade');
            $table->unsignedInteger('surat_id');
            $table->unsignedInteger('surat_no');
            $table->unsignedInteger('dariayat');
            $table->unsignedInteger('sampaiayat');
            $table->date('tgl_sabqi');
            $table->unsignedInteger('nilai');
            $table->text('keterangan')->nullable();
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
