<?php

namespace App\Models\Sakip\KDH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sakip\KDH\PohonKinerjaMisi;
use Illuminate\Database\Eloquent\SoftDeletes;


class PohonKinerjaTujuan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'pohon_kinerja_tujuan';

    protected $keyType = 'string';

    protected $hidden = ['created_by', 'updated_by', 'updated_at'];

    protected $guarded = ['created_at','updated_at'];

    public $incrementing = false;


    public function actions()
    {
        return $this->hasMany(PohonKinerjaMisi::class);
    }

    public function sasaran()
    {
        return $this->hasMany(PohonKinerjaSasaran::class, 'pohon_kinerja_tujuan_id');
    }

    public function indikator_tujuan()
    {
        return $this->hasMany(PohonKinerjaIndikator::class, 'pohon_kinerja_tujuan_id');
    }
}
