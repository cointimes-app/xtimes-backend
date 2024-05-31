<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoolUser extends Model
{
    use HasFactory;

    protected $table = 'pool_user';

    protected $fillable = ['id','pool_id','user_id','is_paid'];

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;
}
