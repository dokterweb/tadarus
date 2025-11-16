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
        Schema::table('tadarus_histories', function (Blueprint $table) {
            $table->unsignedInteger('ustadz_id')
            ->nullable()
            ->after('siswa_id')
            ->comment('ID ustadz yang memberikan nilai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tadarus_histories', function (Blueprint $table) {
            $table->dropColumn('ustadz_id');
        });
    }
};
