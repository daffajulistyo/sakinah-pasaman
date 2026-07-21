<?php

namespace App\Models\Sakip\KDH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sakip\KDH\PohonKinerjaMisi;
use App\Models\Sakip\KDH\PohonKinerjaTujuan;

use Illuminate\Database\Eloquent\SoftDeletes;

class PohonKinerjaVisi extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'pohon_kinerja_visi';

    protected $keyType = 'string';

    protected $hidden = ['created_by', 'updated_by', 'updated_at'];

    protected $guarded = ['created_at','updated_at'];

    public $incrementing = false;


    public function misi()
    {
        return $this->hasMany(PohonKinerjaMisi::class, 'pohon_kinerja_visi_id');
    }
}
