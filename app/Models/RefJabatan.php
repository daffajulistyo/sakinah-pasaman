<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefJabatan extends Model
{
    protected $table = 'ref_jabatan';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
}
