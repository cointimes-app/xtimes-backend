<?php

namespace App\Services;

use App\Models\Pool;
use App\Models\PoolUser;

class BalanceService
{
    public function getBalance($userId)
    {
        return $this->getAvailableAmount($userId);
    }

    private function getAvailableAmount($userId)
    {
        $withdrawablePools = Pool::join('pool_user', 'pools.id', '=', 'pool_user.pool_id')
            ->where('pool_user.user_id', $userId)
            ->where('pool_user.is_paid', false)
            ->lockForUpdate()
            ->get();
    
       return $withdrawablePools->reduce(function($amountSum, $pool){
            return bcadd($amountSum, $pool->reward_amount);
        }, '0');
    }

    public function markAsPaid($userId)
    {
        PoolUser::where('user_id', $userId)
            ->where('is_paid', false)
            ->update(['is_paid' => true]);
    }
}