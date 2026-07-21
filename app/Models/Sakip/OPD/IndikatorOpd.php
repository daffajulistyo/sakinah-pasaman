<?php

namespace App\Models\Sakip\OPD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Sakip\OPD\Renja;
use App\Models\Sakip\OPD\PerjanjianKinerja;


class IndikatorOpd extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'indikator_opd';

    protected $keyType = 'string';

    protected $hidden = ['created_by', 'updated_by', 'updated_at'];

    protected $guarded = ['created_at','updated_at'];

    public $incrementing = false;

    public function renja()
    {
        return $this->hasOne(Renja::class, 'indikator_opd_id');
    }

    public function perjanjian_kinerja()
    {
        return $this->hasOne(PerjanjianKinerja::class, 'indikator_opd_id');
    }
    
}
