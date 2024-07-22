<?php

require_once '../../vendor/autoload.php';

use Epayco\SdkRedeban\DTOs\Present\ShopDto;
use Epayco\SdkRedeban\EpaycoSdkRedebanPresentSales;

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
$shopRequest->terminalTransactionId = 3;
$shopRequest->panCaptureMode = "CHIP";
$shopRequest->pinCapability = "Permitido";
$shopRequest->brand = "MasterCard";
$shopRequest->trackData = "4559860065547154=29042011965785800000";
$shopRequest->accountType = "Credito";
$shopRequest->tokenData = "7ff900008000000000002dee46ae83f17e6e0000000000100000000000002000002d17084024053000296c54f8000706011203a0280000000000000000000000000000000000000000000000000000";
$shopRequest->tokenStatus = "007510001f0300000001";
$shopRequest->discreetData = "4B003149434360d8c8000000000000002200011f03000007a0000000031010000000000000000000";
$shopRequest->totalAmount = 10000;
$shopRequest->instalmentCount = 1;
$shopRequest->reference = 11110;

$additional1 = new stdClass();
$additional1->type = "C4";
$additional1->value = "000001100930";
$additional2 = new stdClass();
$additional2->type = "CH";
$additional2->value = "0 0000000000000000Y 0000   30        1  ";
$shopRequest->additionalData[] = $additional1;
$shopRequest->additionalData[] = $additional2;

$tax1 = new stdClass();
$tax1->taxType = "IVA";
$tax1->amountTax = 100;
$shopRequest->infoTax[] = $tax1;

$shopRequest->detailedAmount = [];

print_r($sdk->reverseTransaction($shopRequest));
