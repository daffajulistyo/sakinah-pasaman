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
        Schema::create('data_uploads', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('judul', 255);
            $table->string('slug', 255);

            $table->foreignUuid('master_opd_id');
            $table->foreign('master_opd_id')->references('id')->on('master_opd');

            $table->text('dokumen');
            $table->integer('type')->default(1);
            $table->year('tahun');
            $table->text('keterangan')->nullable();
 
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
        Schema::dropIfExists('data_uploads');
    }
};
