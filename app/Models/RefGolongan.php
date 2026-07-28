<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefGolongan extends Model
{
    protected $table = 'master_golongan';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
}
