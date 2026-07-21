<?php

namespace App\Models\Sakip\KDH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sakip\KDH\PohonKinerjaVisi;
use Illuminate\Database\Eloquent\SoftDeletes;

class PohonKinerjaMisi extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'pohon_kinerja_misi';

    protected $keyType = 'string';

    protected $hidden = ['created_by', 'updated_by', 'updated_at'];

    protected $guarded = ['created_at','updated_at'];

    public $incrementing = false;

    public function actions()
    {
        return $this->hasMany(PohonKinerjaVisi::class);
    }

    public function tujuan()
    {
        return $this->hasMany(PohonKinerjaTujuan::class, 'pohon_kinerja_misi_id');
    }

}
