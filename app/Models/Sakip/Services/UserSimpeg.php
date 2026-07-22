<?php

namespace App\Models\Sakip\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSimpeg extends Model
{
    use SoftDeletes;

    protected $table = 'user_simpeg';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['id', 'user_id', 'nip', 'nama_pegawai', 'opd_nm', 'jabatan_nm'];
}
