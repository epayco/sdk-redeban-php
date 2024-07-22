<?php

require '../../vendor/autoload.php';

use Epayco\SdkRedeban\DTOs\Present\VoidDto;
use Epayco\SdkRedeban\EpaycoSdkRedebanPresentSales;

$sdk = new EpaycoSdkRedebanPresentSales();

$voidRequest = new VoidDto;

$voidRequest->terminalId = "xxxxxxxxxxxxxx";
$voidRequest->acquirerId = "xxxxxxxxxxxxxx";
$voidRequest->terminalTransactionId = "xxxxxxxxxxxxxx";
$voidRequest->panCaptureMode = "xxxxxxxxxxxxxx";
$voidRequest->pinCapability = "xxxxxxxxxxxxxx";
$voidRequest->franchise = "xxxxxxxxxxxxxx";
$voidRequest->track = "xxxxxxxxxxxxxx";
$voidRequest->accountType = "xxxxxxxxxxxxxx";
$voidRequest->tokenData = "xxxxxxxxxxxxxx";
$voidRequest->tokenStatus = "xxxxxxxxxxxxxx";
$voidRequest->discreteData = "xxxxxxxxxxxxxx";
$voidRequest->totalAmount = 10;
$voidRequest->taxAmount = 0;
$voidRequest->amountBase = 0;
$voidRequest->VATRefundBase = 0;
$voidRequest->reference = "xxxxxxxxxxxxxx";
$voidRequest->instalmentCount = 0;
$voidRequest->authorizerTransactionId = "xxxxxxxxxxxxxx";

print_r($sdk->cancelTransaction($voidRequest));
