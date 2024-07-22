<?php

require_once '../../vendor/autoload.php';

use Epayco\SdkRedeban\DTOs\Present\CancelDto;
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

$cancelRequest = new CancelDto();

$cancelRequest->terminalType = "MPOS";
$cancelRequest->terminalId = "EPAYTERM";
$cancelRequest->acquirerId = "10203047";
$cancelRequest->terminalTransactionId = 30001;
$cancelRequest->panCaptureMode = "CHIP";
$cancelRequest->pinCapability = "Permitido";
$cancelRequest->brand = "MasterCard";
$cancelRequest->trackData = "4559860065547154=29042011965785800000";
$cancelRequest->accountType = "Credito";
$cancelRequest->tokenData = "7ff900008000000000002dee46ae83f17e6e0000000000100000000000002000002d17084024053000296c54f8000706011203a0280000000000000000000000000000000000000000000000000000";
$cancelRequest->tokenStatus = "007510001f0300000001";
$cancelRequest->discreetData = "4B003149434360d8c8000000000000002200011f03000007a0000000031010000000000000000000";
$cancelRequest->totalAmount = 10000;
$cancelRequest->instalmentCount = 1;
$cancelRequest->reference = 1111001;
$cancelRequest->approvalNumber = '053260';
$cancelRequest->transactionIdAuthorizer = '30001';

$additional1 = new stdClass();
$additional1->type = "C4";
$additional1->value = "000001100930";
$additional2 = new stdClass();
$additional2->type = "CH";
$additional2->value = "0 0000000000000000Y 0000   30        1  ";
$cancelRequest->additionalData[] = $additional1;
$cancelRequest->additionalData[] = $additional2;

$tax1 = new stdClass();
$tax1->taxType = "IVA";
$tax1->amountTax = 100;
$cancelRequest->infoTax[] = $tax1;

$cancelRequest->detailedAmount = [];

print_r($sdk->cancelTransaction($cancelRequest));
