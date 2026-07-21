<?php

namespace App\Models\Sakip\KDH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PohonKinerjaSasaran extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'pohon_kinerja_sasaran';

    protected $keyType = 'string';

    protected $hidden = ['created_by', 'updated_by', 'updated_at'];

    protected $guarded = ['created_at','updated_at'];

    public $incrementing = false;

    public function actions()
    {
        return $this->hasMany(PohonKinerjaMisi::class);
    }

    public function indikator_sasaran()
    {
        return $this->hasMany(PohonKinerjaIndikator::class, 'pohon_kinerja_sasaran_id');
    }

    public function cascading()
    {
        return $this->hasMany(Cascading::class, 'pohon_kinerja_sasaran_id');
    }

   
}
