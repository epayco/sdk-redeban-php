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
$purchaseRequest->terminalId = "SRB00315";
$purchaseRequest->acquirerId = "10203158";
$purchaseRequest->terminalTransactionId = 2;
$purchaseRequest->panCaptureMode = "Manual";
$purchaseRequest->pinCapability = "Virtual";
$purchaseRequest->totalAmount = 11900;
$purchaseRequest->ivaTax = 1900;
$purchaseRequest->baseTax = 10000;
$purchaseRequest->reference = 2222222;
$purchaseRequest->instalmentsQuantity = 1;
$purchaseRequest->cardType = 'idTarjetaCredito';
$purchaseRequest->franchise = 'VISA';
$purchaseRequest->cardNumber = "4513076106055348";
$purchaseRequest->expirationDate = "2025-12-31";
$purchaseRequest->securityCode = '';
$purchaseRequest->personDocumentType = 'CC';
$purchaseRequest->personDocumentNumber = '1053814720';

$purchaseRequest->threeDSEci = '02'; // Para 3DS
$purchaseRequest->threeDSDirectoryServerTransactionId = 'YjcwZWYyMGEtODJjNy00ODI0LWJkNTgtZjAxYjhkMTEyN2Zl';
$purchaseRequest->threeDSSecVersion = '2.0';
$purchaseRequest->threeDSAcctAuthValue = 'xgQYYgZVAAAAAAAAAAAAAAAAAAAA';

$purchaseRequest->softDescMarcTerminal = 'EPAYCO*JorgeFlorezMart';// Para soft descriptor
$purchaseRequest->softDescFacilitatorId = '00000266029';
$purchaseRequest->softDescSalesOrgId = '00000266029';
$purchaseRequest->softDescSubMerchId = 627579;

//{"cabeceraSolicitud":{"infoPuntoInteraccion":{"idTerminal":"SRB00315","idAdquiriente":"10203158","idTransaccionTerminal":3379,"tipoTerminal":"WEB","modoCapturaPAN":"Manual",
//"capacidadPIN":"Virtual"}},"idPersona":{"tipoDocumento":"CC","numDocumento":"1053814720"},"infoMedioPago":{"idTarjetaCredito":{"franquicia":"VISA","numTarjeta":"4513076106055348",
//"fechaExpiracion":"2025-12-31","codVerificacion":""}},"infoCompra":{"montoTotal":11900,"infoImpuestos":{"tipoImpuesto":"IVA","monto":1900,"baseImpuesto":10000},"referencia":101626299,
//"cantidadCuotas":1}}

//{"cabeceraSolicitud":{"infoPuntoInteraccion":{"idTerminal":"SRB00315","idAdquiriente":"11174646","idTransaccionTerminal":2850,"tipoTerminal":"WEB","modoCapturaPAN":"Manual",
//"capacidadPIN":"Virtual"}},"idPersona":{"tipoDocumento":"CC","numDocumento":"17657489"},"infoMedioPago":{"idTarjetaCredito":{"franquicia":"MasterCard","numTarjeta":"5265570074946840",
//"fechaExpiracion":"2025-12-31","codVerificacion":"123"}},"infoCompra":{"montoTotal":"10000.00","infoImpuestos":{"tipoImpuesto":"IVA","monto":"0.00","baseImpuesto":"0.00"},"referencia":101626310,"cantidadCuotas":1},"infoAdicional":{"eci":"02","directoryServerTrxID":"b70ef20a-82c7-4824-bd58-f01b8d1127fe","secVersion":"2.0","acctAuthValue":"xgQYYgZVAAAAAAAAAAAAAAAAAAAA"}}


print_r($sdk->createTransaction($purchaseRequest));
