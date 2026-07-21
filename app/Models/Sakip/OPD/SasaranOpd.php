<?php

namespace App\Models\Sakip\OPD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Sakip\OPD\TujuanOpd;
use App\Models\Sakip\OPD\IndikatorOpd;
use App\Models\Sakip\OPD\CascadingOpd;
use App\Models\Sakip\OPD\RenjaProgram;
use App\Models\Sakip\OPD\PerjanjianKinerjaProgram;

class SasaranOpd extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'sasaran_opd';

    protected $keyType = 'string';

    protected $hidden = ['created_by', 'updated_by', 'updated_at'];

    protected $guarded = ['created_at','updated_at'];

    public $incrementing = false;

    public function indikator_sasaran()
    {
        return $this->hasMany(IndikatorOpd::class, 'sasaran_opd_id');
    }

    public function program_pendukung()
    {
        return $this->hasMany(CascadingOpd::class, 'sasaran_opd_id');
    }

    public function program_renja()
    {
        return $this->hasMany(RenjaProgram::class, 'sasaran_opd_id');
    }

    public function program_perjanjian_kinerja()
    {
        return $this->hasMany(PerjanjianKinerjaProgram::class, 'sasaran_opd_id');
    }

    public function anggaran_renja()
    {
        return $this->hasOne(RenjaProgram::class, 'sasaran_opd_id');
    }

}
