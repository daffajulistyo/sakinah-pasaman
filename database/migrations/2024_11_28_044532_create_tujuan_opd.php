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
        Schema::create('tujuan_opd', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pohon_kinerja_sasaran_id')->nullable();
            $table->foreign('pohon_kinerja_sasaran_id')->references('id')->on('pohon_kinerja_sasaran');

            $table->foreignUuid('master_opd_id')->nullable();
            $table->foreign('master_opd_id')->references('id')->on('master_opd');

            $table->unsignedInteger('order')->default(0);
            $table->text('tujuan');            
            $table->boolean('is_direct')->default(false);
            $table->boolean('is_active')->default(false);

            
            $table->foreignUuid('pohon_kinerja_visi_id');
            $table->foreign('pohon_kinerja_visi_id')->references('id')->on('pohon_kinerja_visi');

            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tujuan_opd');
    }
};
