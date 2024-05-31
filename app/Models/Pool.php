<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pool extends Model
{
    use HasFactory;

    protected $fillable = ['reward_amount', 'end_at'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function ledger()
    {
        return $this->hasMany(PoolLedger::class);
    }

    public static function getLastActivePool()
    {
        return Pool::whereNull('end_at')->orderBy('id', 'desc')->first();
    }
}
