<?php

namespace App\Models\Sakip\OPD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LangkahSkp extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'skp_langkah';

    protected $keyType = 'string';

    protected $hidden = ['created_by', 'updated_by', 'updated_at'];

    protected $guarded = ['created_at','updated_at'];

    public $incrementing = false;
}
