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
        Schema::create('nilai_infografis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('periode_penilaian_id');
            $table->foreign('periode_penilaian_id')->references('id')->on('periode_penilaians');
            $table->string('infografis_id');
            $table->foreign('infografis_id')->references('id')->on('infografis');
            $table->integer('nilai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_infografis');
    }
};