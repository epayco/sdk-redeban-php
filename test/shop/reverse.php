<?php

require_once '../../vendor/autoload.php';

use Epayco\SdkRedeban\EpaycoSdkRedebanPresentSales;

use Epayco\SdkRedeban\DTOs\ShopDto;

$localCert = file_get_contents("../certs/local_cert.pem");
$localPrivateKey = file_get_contents("../certs/local_key.pem");
$redebanKey = file_get_contents("../certs/rbm_cert.pem");

$sdk = new EpaycoSdkRedebanPresentSales();
$sdk->setUsername("TestAliEpayco")
    ->setPassword("TestAliEpayco.2023")
    ->setLocalCert($localCert)
    ->setLocalPrivateKey($localPrivateKey)
    ->setRedebanCert($redebanKey)
    ->setEnvironment("test");

$shopRequest = new ShopDto;

$shopRequest->terminalType = "MPOS";
$shopRequest->terminalId = "EPAYTERM";
$shopRequest->acquirerId = "10203047";
$shopRequest->terminalTransactionId = "1";
$shopRequest->panCaptureMode = "CHIP";
$shopRequest->pinCapability = "Permitido";
$shopRequest->brand = "MasterCard";
$shopRequest->trackData = "XXXXXXXXXXXXXXXX=XXXXXXXXXXXXXXX";
$shopRequest->accountType = "Credito";
$shopRequest->tokenData = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
$shopRequest->tokenStatus = "XXXXXXXXXXXXXX XX";
$shopRequest->discreetData = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
$shopRequest->totalAmount = 20;
$shopRequest->taxType = 'IVA';
$shopRequest->amountTax = 0;
$shopRequest->detailedAmountType = "aaaaaaaaaa";
$shopRequest->detailedAmount = 0.0;
$shopRequest->installmentCount = 1;
$shopRequest->reference = 2;
$shopRequest->additionalData = "aaaaaaaaaa";

print_r($sdk->reverseTransaction($shopRequest));
