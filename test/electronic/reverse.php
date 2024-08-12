<?php

require_once '../../vendor/autoload.php';

use Epayco\SdkRedeban\DTOs\Electronic\PurchaseDto;
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

$purchaseRequest = new PurchaseDto();

$purchaseRequest->terminalType = "WEB";
$purchaseRequest->terminalId = "SRB00315";//ESB20107
$purchaseRequest->acquirerId = "11174646";//11174653
$purchaseRequest->terminalTransactionId = 333335;
$purchaseRequest->panCaptureMode = "Manual";
$purchaseRequest->pinCapability = "Virtual";
$purchaseRequest->totalAmount = 5000;
$purchaseRequest->ivaTax = 500;
$purchaseRequest->baseTax = 500;
$purchaseRequest->reference = 222225;
$purchaseRequest->instalmentsQuantity = 1;
$purchaseRequest->paymentIndicator = "UCOF"; // Solo para MasterCard
$purchaseRequest->paymentType = "0";
$purchaseRequest->recurringAmountType = '';

$purchaseRequest->cardType = 'idTarjetaCredito';
$purchaseRequest->franchise = 'MasterCard';
$purchaseRequest->cardNumber = "5471301455910662";
$purchaseRequest->expirationDate = "2028-06-01";
$purchaseRequest->securityCode = '334';
$purchaseRequest->personDocumentType = 'CC';
$purchaseRequest->personDocumentNumber = '10613254';

$purchaseRequest->threeDSEci = '02'; // Para 3DS
$purchaseRequest->threeDSDirectoryServerTransactionId = 'b70ef20a-82c7-4824-bd58-f01b8d1127fe';
$purchaseRequest->threeDSSecVersion = '2.0';
$purchaseRequest->threeDSAcctAuthValue = 'xgQYYgZVAAAAAAAAAAAAAAAAAAAA';

$purchaseRequest->softDescMarcTerminal = 'EPAYCO*JorgeFlorezMart';// Para soft descriptor
$purchaseRequest->softDescFacilitatorId = '00000266029';
$purchaseRequest->softDescSalesOrgId = '00000266029';
$purchaseRequest->softDescSubMerchId = 627579;

$response = $sdk->undoTransaction($purchaseRequest);
$response = json_decode($response);
print_r($response);
