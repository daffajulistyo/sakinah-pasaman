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
        Schema::create('skp_periode', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('nip', 100);

            $table->foreignUuid('master_opd_id');
            $table->foreign('master_opd_id')->references('id')->on('master_opd');

            $table->date('periode_awal');
            $table->date('periode_akhir');
            $table->year('tahun');
            $table->string('pendekatan', 50);
            $table->boolean('is_active')->default(true);
            $table->date('batas_input')->nullable();    
            
            $table->text('jns_jbtn_id')->nullable();
            $table->text('jns_jbtn_nm')->nullable();
            $table->text('jabatan_id')->nullable();
            $table->text('jabatan_nm')->nullable();
            $table->text('eselon_id')->nullable();
            $table->text('eselon_nm')->nullable();
            

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
        Schema::dropIfExists('skp_periode');
    }
};
