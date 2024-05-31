<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoolUser extends Model
{
    use HasFactory;

    protected $table = 'pool_user';

    protected $fillable = ['is_paid'];
}
