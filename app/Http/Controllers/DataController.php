<?php
namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Pool;
use App\Models\PoolLedger;
use App\Models\PoolUser;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DataController extends Controller
{

    public function getProfile()
    {
        $user = Auth::user();
        $profile = $user->userProfile->data ?? null;

        return response()->json([ 'profile' => $profile]);
    }

    public function saveUserProfile(Request $request)
    {
        $request->validate([
            'profile' => 'required|array',
        ]);

        $user = Auth::user();

        DB::beginTransaction();

        try{

            UserProfile::updateOrCreate(
                ['user_id' => $user->id],
                ['data' => $request->profile]
            );

            $pool = $this->getActivePool();
            $poolUserExists = $pool->users()->where('user_id', $user->id)->exists();

            if(!$poolUserExists){
                $this->addUserToPool($user->id);
                $this->depositReward($pool->reward_amount);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
            
        return response()->json(status: 201);
    }
    private function getActivePool()
    {
        $pool = Pool::getLastActivePool();

        if(!$pool){
            $pool = Pool::create([
                'reward_amount' => env('POOL_REWARD_AMOUNT_DEFAULT'),
            ]);
        }

        return $pool;
    }

    public function addUserToPool($userId)
    {
        $pool = $this->getActivePool();

        PoolUser::create([
            'id'=> Str::uuid(),
            'pool_id' => $pool->id,
            'user_id' => $userId,
        ]);
    }

    public function depositReward(float $amount)
    {
        $pool = $this->getActivePool();

        PoolLedger::create([
            'pool_id' => $pool->id,
            'type' => PoolLedger::TYPE_REWARD,
            'amount' => $amount,
        ]);
    }
}