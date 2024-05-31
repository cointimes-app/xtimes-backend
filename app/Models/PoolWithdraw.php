<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoolWithdraw extends Model
{
    use HasFactory;

    protected $table = 'pool_withdrawals';

    protected $fillable = ['pool_id','wallet_address', 'amount'];

    public function pool()
    {
        return $this->belongsTo(Pool::class);
    }
}
