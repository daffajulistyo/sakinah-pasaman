<?php

namespace App\Models\Sakip\MASTER;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class MasterOpd extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
    protected $table = 'master_opd';

    protected $keyType = 'string';

    protected $hidden = ['created_by', 'updated_by', 'updated_at'];

    protected $guarded = ['created_at','updated_at'];

    public $incrementing = false;

    public function indikator()
    {
        return $this->belongsToMany(PohonKinerjaIndikator::class, 'opd_pendukung_indikator');
    }

}
