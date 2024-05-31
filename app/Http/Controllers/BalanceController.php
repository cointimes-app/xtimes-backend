<?php
namespace App\Http\Controllers;

use App\Services\BalanceService;
use Illuminate\Support\Facades\Auth;

class BalanceController extends Controller
{
    public function getBalance(BalanceService $service)
    {
        $user = Auth::user();
        $balance = $service->getBalance($user->id);
        
        return response()->json([
            'balance' => $balance,
        ]);
    }
    
}