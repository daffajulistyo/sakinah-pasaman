<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ref_jabatan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode', 50);
            $table->string('nama', 200);
            $table->foreignUuid('ref_jenis_jabatan_id')->nullable()->constrained('ref_jenis_jabatan');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ref_jabatan');
    }
};
