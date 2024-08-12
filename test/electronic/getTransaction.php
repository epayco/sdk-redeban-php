<?php

require_once '../../vendor/autoload.php';

use Epayco\SdkRedeban\DTOs\Electronic\ShowTransactionDto;
use Epayco\SdkRedeban\ElectronicPurchaseIntegration;


$localCert = file_get_contents("crt/local_cert.pem");
$localPrivateKey = file_get_contents("crt/local_key.pem");
$redebanKey = file_get_contents("crt/rbm_cert.pem");

$sdk = new ElectronicPurchaseIntegration();
$sdk->setUsername("TestAliEpayco")
    ->setPassword("TestAliEpayco.2023")
    ->setLocalCert($localCert)
    ->setLocalPrivateKey($localPrivateKey)
    ->setRedebanCert($redebanKey)
    ->setEnvironment("test")
    ->build();

$showTransactionDto = new ShowTransactionDto();

$showTransactionDto->terminalType = "WEB";
$showTransactionDto->terminalId = "SRB00315";
$showTransactionDto->acquirerId = "11174646";
$showTransactionDto->terminalTransactionId = 333335;
$showTransactionDto->transactionDate = "2024-08-08";

$response = $sdk->getTransaction($showTransactionDto);
$response = json_decode($response);
print_r($response);
