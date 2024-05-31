<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Soneso\StellarSDK\Asset;
use Soneso\StellarSDK\KeyPair;
use Soneso\StellarSDK\Network;
use Soneso\StellarSDK\Server;
use Soneso\StellarSDK\TransactionBuilder;
use Soneso\StellarSDK\Xdr\XdrChangeTrustAsset;

class CreateToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Token $XTIMES';

    /**
     * Execute the console command.
     */
    public function handle()
    {
       
// Server connection (use testnet for testing)
$server = Server::testNet();

// Keypairs for issuing and distribution accounts
$issuingKeypair = KeyPair::fromSeed('SXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX');
$distributionKeypair = KeyPair::fromSeed('SYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYY');

// Load accounts
$issuingAccount = $server->loadAccount($issuingKeypair->getAccountId());
$distributionAccount = $server->loadAccount($distributionKeypair->getAccountId());

// Define the asset
$assetCode = 'XTIMES';
$asset = Asset::newCustomAsset($assetCode, $issuingKeypair->getAccountId());

try {
    // Step 1: Create a trustline from the distribution account to the issuing account
    $transaction = (new TransactionBuilder($distributionAccount))
        ->addChangeTrustOperation(new XdrChangeTrustAsset($asset->getType(), $asset->getCode(), $asset->getIssuer()), '1000000') // Trust limit
        ->setTimeout(30)
        ->build();
    $transaction->sign($distributionKeypair);
    $response = $server->submitTransaction($transaction);
    echo "Trustline created successfully!\n";

    // Step 2: Issue the asset
    $transaction = (new TransactionBuilder($issuingAccount))
        ->addPaymentOperation($distributionKeypair->getAccountId(), $asset, '1000') // Amount to issue
        ->setTimeout(30)
        ->build();
    $transaction->sign($issuingKeypair);
    $response = $server->submitTransaction($transaction);
    echo "Asset issued successfully!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
    }
}
