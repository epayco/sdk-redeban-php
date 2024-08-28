<?php

require_once '../../vendor/autoload.php';

use Epayco\SdkRedeban\ApiSacIntegration;
use Epayco\SdkRedeban\DTOs\Apisac\RefundDto;

$localCert = file_get_contents("crt/local_cert.pem");
$localPrivateKey = file_get_contents("crt/local_key.pem");
$redebanKey = file_get_contents("crt/rbm_cert.pem");
$redebanEncrypCert = file_get_contents("crt/rbm_public_key.pem");

$sdk = new ApiSacIntegration();
$sdk->setUsername("testEpayco")
    ->setPassword("testEpayco.2020")
    ->setLocalKey($localPrivateKey)
    ->setLocalCert($localCert)
    ->setRedebanCert($redebanKey)
    ->setLocalEncryptCert($localPrivateKey)
    ->setRedebanEncryptCert($redebanEncrypCert)
    ->setEnvironment("test")
    ->build();

$refundRequest = new RefundDto();

$refundRequest->transactionDate = "2024-08-14";
$refundRequest->acquirerId = "10003937";
$refundRequest->cardNumber = "1003";
$refundRequest->totalAmount = 8790;
$refundRequest->refundApprovalNumber = "233111";
//$refundRequest->adjustmentAmount = 10;

$response = $sdk->refundTransaction($refundRequest);
$response = json_decode($response);
print_r($response);
