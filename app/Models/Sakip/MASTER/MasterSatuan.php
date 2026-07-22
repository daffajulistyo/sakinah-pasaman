<?php

namespace App\Models\Sakip\MASTER;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Sakip\KDH\PohonKinerjaIndikator;

class MasterSatuan extends Model
{
    use HasFactory;
   
    
    protected $table = 'master_satuan';

    protected $keyType = 'string';

    protected $hidden = ['created_by', 'updated_by', 'updated_at'];

    protected $guarded = ['created_at','updated_at'];

    public $incrementing = false;


    public function indikator(): BelongsTo
    {
        return $this->belongsTo(PohonKinerjaIndikator::class);
    }

}
