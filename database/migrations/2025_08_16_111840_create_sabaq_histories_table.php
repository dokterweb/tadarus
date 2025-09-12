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
            $table->foreign('sabaq_id')->references('id')->on('sabaqs')->onDelete('cascade');
            $table->unsignedInteger('surat_id');
            $table->unsignedInteger('surat_no');
            $table->unsignedInteger('dariayat');
            $table->unsignedInteger('sampaiayat');
            $table->date('tgl_sabaq');
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
        Schema::dropIfExists('sabaq_histories');
    }
};
