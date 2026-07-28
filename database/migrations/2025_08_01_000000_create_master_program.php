<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_program', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode_program', 50);
            $table->string('nama_program');
            $table->string('kode_skpd', 50);
            $table->year('tahun')->default('2025');
            $table->boolean('is_active')->default(true);
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_program');
    }
};
