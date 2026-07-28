<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterProgram extends Model
{
    protected $table = 'master_program';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
}
