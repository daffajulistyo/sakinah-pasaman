<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;

class UserSimpeg extends Model
{
    protected $table = 'user_simpeg';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['id', 'user_id', 'nip', 'nama_pegawai', 'opd_nm', 'jabatan_nm'];
}
