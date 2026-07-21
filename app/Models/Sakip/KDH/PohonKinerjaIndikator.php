<?php

namespace App\Models\Sakip\KDH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Sakip\MASTER\MasterSatuan;

class PohonKinerjaIndikator extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'pohon_kinerja_indikator';

    protected $keyType = 'string';

    protected $hidden = ['created_by', 'updated_by', 'updated_at'];

    protected $guarded = ['created_at','updated_at'];

    public $incrementing = false;


    public function opd_pendukung()
    {
        return $this->belongsToMany(MasterOpd::class, 'opd_pendukung_indikator');
    }

    public function tujuan()
    {
        return $this->hasMany(PohonKinerjaTujuan::class);
    }

    public function sasaran()
    {
        return $this->hasMany(PohonKinerjaSasaran::class);
    }


    public function satuan()
    {
        return $this->hasOne(MasterSatuan::class, 'id', 'satuan_id');
    }
}
