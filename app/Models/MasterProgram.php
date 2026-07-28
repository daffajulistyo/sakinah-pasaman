<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Sakip\MASTER\MasterOpd;

class MasterProgram extends Model
{
    protected $table = 'master_program';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    public function opd()
    {
        return $this->belongsTo(MasterOpd::class, 'master_opd_id');
    }

    public function kegiatans()
    {
        return $this->hasMany(MasterKegiatan::class, 'master_program_id');
    }
}
