<?php

namespace App\Models;

use App\Models\Sakip\MASTER\MasterOpd;
use Illuminate\Database\Eloquent\Model;

class RefSubOpd extends Model
{
    protected $table = 'master_sub_opd';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    public function opd()
    {
        return $this->belongsTo(MasterOpd::class, 'master_opd_id');
    }
}
