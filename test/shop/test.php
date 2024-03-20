<?php

require '../../vendor/autoload.php';

use Epayco\SdkRedeban\EpaycoSdkRedebanPresentSales;

use Epayco\SdkRedeban\DTOs\ShopDto;

$sdk = new EpaycoSdkRedebanPresentSales();
$sdk->setUsername("xxxx")->setPassword("xxxxx")->setLocalPublicKey("xxxx")->setLocalPrivateKey("xxxx")->setPublicKey("xxxxx");
$shopRequest = new ShopDto;

$shopRequest->terminalType = "aaaaaaaaaa";
$shopRequest->terminalId = "aaaaaaaaaa";
$shopRequest->acquirerId = "aaaaaaaaaa";
$shopRequest->terminalTransactionId = "aaaaaaaaaa";
$shopRequest->panCaptureMode = "aaaaaaaaaa";
$shopRequest->pinCapability = "aaaaaaaaaa";
$shopRequest->brand = "aaaaaaaaaa";
$shopRequest->trackData = "aaaaaaaaaa";
$shopRequest->accountType = "aaaaaaaaaa";
$shopRequest->tokenData = "aaaaaaaaaa";
$shopRequest->tokenStatus = "aaaaaaaaaa";
$shopRequest->discreetData = "aaaaaaaaaa";
$shopRequest->totalAmount = 1.0;
$shopRequest->amountTax = 0;
$shopRequest->detailedAmountType = "aaaaaaaaaa";
$shopRequest->detailedAmount = 0.0;
$shopRequest->installmentCount = 1;
$shopRequest->reference = "aaaaaaaaaa";
$shopRequest->additionalDataType = "aaaaaaaaaa";

print_r($sdk->processTransaction($shopRequest));
