<?php

namespace App\Models\Sakip\KDH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class OpdPendukungIndikator extends Model
{
    use HasFactory;   

    protected $dates = ['deleted_at'];

    protected $table = 'opd_pendukung_indikator';

    protected $keyType = 'string';

    protected $hidden = ['created_by', 'updated_by', 'updated_at'];

    protected $guarded = ['created_at','updated_at'];

    public $incrementing = false;

    public $timestamps = true;
    
    public function indikator()
    {
        return $this->belongsToMany(PohonKinerjaIndikator::class);
    }
}
