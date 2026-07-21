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
        Schema::create('sasaran_opd', function (Blueprint $table) {
            $table->uuid('id')->primary()
            ;
            $table->foreignUuid('tujuan_opd_id');
            $table->foreign('tujuan_opd_id')->references('id')->on('tujuan_opd');

            $table->foreignUuid('master_opd_id')->nullable();
            $table->foreign('master_opd_id')->references('id')->on('master_opd');

            $table->unsignedInteger('order')->default(0);
            $table->string('parent_id', 100)->default(0);
            $table->text('sasaran');      
            $table->boolean('is_active')->default(false);
            $table->unsignedInteger('level')->default(0);

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
        Schema::dropIfExists('sasaran_opd');
    }
};
