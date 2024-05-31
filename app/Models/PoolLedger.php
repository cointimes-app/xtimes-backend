<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoolLedger extends Model
{
    use HasFactory;

    protected $table = 'pool_ledger';

    protected $fillable = ['pool_id','type', 'amount'];

    const TYPE_REWARD = 'reward';
    const TYPE_WITHDRAW = 'withdraw';

    public function pool()
    {
        return $this->belongsTo(Pool::class);
    }
}
