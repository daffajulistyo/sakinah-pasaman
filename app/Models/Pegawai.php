<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pegawai extends Model
{
    use SoftDeletes;

    protected $table = 'pegawai';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function masterOpd()
    {
        return $this->belongsTo(Sakip\MASTER\MasterOpd::class, 'master_opd_id');
    }

    public function refEselon()
    {
        return $this->belongsTo(RefEselon::class, 'ref_eselon_id');
    }

    public function refGolongan()
    {
        return $this->belongsTo(RefGolongan::class, 'ref_golongan_id');
    }

    public function refJenisJabatan()
    {
        return $this->belongsTo(RefJenisJabatan::class, 'ref_jenis_jabatan_id');
    }

    public function refJabatan()
    {
        return $this->belongsTo(RefJabatan::class, 'ref_jabatan_id');
    }
}
