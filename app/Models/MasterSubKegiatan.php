<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterSubKegiatan extends Model
{
    protected $table = 'master_sub_kegiatan';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    public function kegiatan()
    {
        return $this->belongsTo(MasterKegiatan::class, 'master_kegiatan_id');
    }
}
