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
        Schema::create('pohon_kinerja_sasaran', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pohon_kinerja_tujuan_id')->nullable();
            $table->foreign('pohon_kinerja_tujuan_id')->references('id')->on('pohon_kinerja_tujuan');
            $table->unsignedInteger('parent_id')->default(0);
            $table->unsignedInteger('order')->default(0);
            $table->text('sasaran');
            $table->boolean('is_active')->default(false);
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pohon_kinerja_sasaran');
    }
};
