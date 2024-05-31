<?php
namespace App\Http\Controllers;

use App\Services\BalanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Soneso\StellarSDK\Asset;
use Soneso\StellarSDK\AssetTypeCreditAlphanum12;
use Soneso\StellarSDK\CreateAccountOperationBuilder;
use Soneso\StellarSDK\Crypto\KeyPair;
use Soneso\StellarSDK\Network;
use Soneso\StellarSDK\PaymentOperationBuilder;
use Soneso\StellarSDK\StellarSDK;
use Soneso\StellarSDK\TransactionBuilder;

class WithdrawController extends Controller
{
    public function withdraw(Request $request, BalanceService $balanceService)
    {
        $request->validate([
            'address' => 'required|string',
        ]);

        $user = Auth::user();
        $balance = $balanceService->getBalance($user->id);

        if(! $this->isGreaterThanZero($balance)){
            return response()->json([
                'message' => 'Insufficient balance',
            ], 400);
        }

        $isSuccess = $this->processWithdraw($request->address, $balance);

        if ($isSuccess) {

            $balanceService->markAsPaid($user->id);

            return response()->json([
                'message' => 'Success',
            ]);
        }
        
        return response()->json([
            'message' => 'Failed',
        ]);
        
    }

    public function processWithdraw($address, $amount): bool
    {
        $sdk = StellarSDK::getTestNetInstance();
        
        $keyA = KeyPair::fromSeed(env('STELLAR_SEED'));
        $asset = new AssetTypeCreditAlphanum12('XTIMES', 'GAKGGKHIYBYZ3A2MHX2MRD7MX76IJBZQLIUMIQXP4OGYZQRAROQVYU35');

        $accA = $sdk->requestAccount($keyA->getAccountId());
        $paymentOperation = (new PaymentOperationBuilder($address, $asset, $amount))->build();

        $transaction = (new TransactionBuilder($accA))
            ->addOperation($paymentOperation)
            ->build();

        $transaction->sign($keyA, Network::testnet());

        $response = $sdk->submitTransaction($transaction);

        return $response->isSuccessful();
    }

    private function isGreaterThanZero($amount): bool
    {
        return bccomp($amount, '0') == 1;
    }

    function getProfileFromData($data)
    {
        return [];
    }
}