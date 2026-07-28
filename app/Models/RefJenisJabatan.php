<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefJenisJabatan extends Model
{
    protected $table = 'master_jenis_jabatan';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
}
