<?php

namespace App\Models\Sakip\OPD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Sakip\OPD\SasaranOpd;
use App\Models\Sakip\OPD\IndikatorOpd;

class TujuanOpd extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'tujuan_opd';

    protected $keyType = 'string';

    protected $hidden = ['created_by', 'updated_by', 'updated_at'];

    protected $guarded = ['created_at','updated_at'];

    public $incrementing = false;

    public function sasaran()
    {
        return $this->hasMany(SasaranOpd::class, 'tujuan_opd_id');
    }

    public function indikator_tujuan()
    {
        return $this->hasMany(IndikatorOpd::class, 'tujuan_opd_id');
    }
}
