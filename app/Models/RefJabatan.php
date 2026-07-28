<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefJabatan extends Model
{
    protected $table = 'master_jabatan';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    public function jenisJabatan()
    {
        return $this->belongsTo(RefJenisJabatan::class, 'ref_jenis_jabatan_id');
    }
}
