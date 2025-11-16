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
        Schema::create('iqrohistories', function (Blueprint $table) {
            $table->id();
            $table->date('tgl_iqro');
            $table->foreignId('siswa_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('jenisiqro_id');
            $table->unsignedInteger('hal_awal');
            $table->unsignedInteger('hal_akhir');
            $table->string('nilaibacaan');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iqrohistories');
    }
};
