<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKegiatan extends Model
{
    protected $table = 'master_kegiatan';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    public function program()
    {
        return $this->belongsTo(MasterProgram::class, 'master_program_id');
    }

    public function subKegiatans()
    {
        return $this->hasMany(MasterSubKegiatan::class, 'master_kegiatan_id');
    }
}
