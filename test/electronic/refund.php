<?php

require_once '../../vendor/autoload.php';

use Epayco\SdkRedeban\DTOs\Electronic\RefundDto;
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

$refundRequest = new RefundDto();

$refundRequest->terminalType = "WEB";
$refundRequest->terminalId = "SRB00315";
$refundRequest->acquirerId = "11174646";
$refundRequest->terminalTransactionId = 333339;
$refundRequest->panCaptureMode = "Manual";
$refundRequest->pinCapability = "Virtual";
$refundRequest->totalAmount = 5000;
$refundRequest->ivaTax = 500;
$refundRequest->baseTax = 500;
$refundRequest->reference = 222229;
$refundRequest->instalmentsQuantity = 1;
$refundRequest->paymentIndicator = "UCOF"; // Solo para MasterCard
$refundRequest->paymentType = "0";
$refundRequest->recurringAmountType = '';

$refundRequest->cardType = 'idTarjetaCredito';
$refundRequest->franchise = 'MasterCard';
$refundRequest->cardNumber = "5471301455910662";
$refundRequest->expirationDate = "2028-06-01";
$refundRequest->personDocumentType = 'CC';
$refundRequest->personDocumentNumber = '10613254';

$refundRequest->refundApprovalNumber = '948135';
$refundRequest->refundAuthorizerTransactionId = 333339;

$response = $sdk->refundTransaction($refundRequest);
$response = json_decode($response);
print_r($response);
