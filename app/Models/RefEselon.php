<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefEselon extends Model
{
    protected $table = 'master_eselon';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
}
