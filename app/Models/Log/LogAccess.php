<?php

namespace App\Models\Log;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAccess extends Model
{
    use HasFactory;

    protected $table = 'log_access';

    protected $keyType = 'string';

    protected $hidden = [ 'updated_by', 'updated_at'];

    protected $guarded = ['created_at','updated_at'];

    public $incrementing = false;
}
