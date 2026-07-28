<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Sakip\MASTER\MasterOpd;

class MasterAnggaran extends Model
{
    protected $table = 'master_anggaran';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    public function opd()
    {
        return $this->belongsTo(MasterOpd::class, 'master_opd_id');
    }

    public function program()
    {
        return $this->belongsTo(MasterProgram::class, 'master_program_id');
    }
}
