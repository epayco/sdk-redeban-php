<?php

require_once '../../vendor/autoload.php';

use Epayco\SdkRedeban\ApiSacIntegration;
use Epayco\SdkRedeban\DTOs\Apisac\ShowRefundDto;

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

$refundRequest = new ShowRefundDto();

$refundRequest->transactionDate = "2023-06-01";
$refundRequest->acquirerId = "11174646";
$refundRequest->cardNumber = "2989";
$refundRequest->totalAmount = 72970;
$refundRequest->refundApprovalNumber = "193555";
$refundRequest->gatewayId = 613922;
$refundRequest->adjustmentId = 433422;

$response = $sdk->showRefund($refundRequest);
$response = json_decode($response);
print_r($response);
