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
$purchaseRequest->terminalId = "MANDTE01";
$purchaseRequest->acquirerId = "10003937";
$purchaseRequest->terminalTransactionId = 333340;
$purchaseRequest->panCaptureMode = "Manual";
$purchaseRequest->pinCapability = "Virtual";
$purchaseRequest->totalAmount = 59500;
$purchaseRequest->ivaTax = 9500;
$purchaseRequest->baseTax = 50000;
$purchaseRequest->reference = 222230;
$purchaseRequest->instalmentsQuantity = 1;
$purchaseRequest->paymentIndicator = "UCOF"; // Solo para MasterCard
$purchaseRequest->paymentType = "0";
$purchaseRequest->recurringAmountType = '';

$purchaseRequest->cardType = 'idTarjetaCredito';
$purchaseRequest->franchise = 'MasterCard';
$purchaseRequest->cardNumber = "5536439988368272";
$purchaseRequest->expirationDate = "2024-06-01";
$purchaseRequest->securityCode = '355';
$purchaseRequest->personDocumentType = 'CC';
$purchaseRequest->personDocumentNumber = '10613254';

$purchaseRequest->threeDSEci = '02'; // Para 3DS
$purchaseRequest->threeDSDirectoryServerTransactionId = 'b70ef20a-82c7-4824-bd58-f01b8d1127fe';
$purchaseRequest->threeDSSecVersion = '2.0';
$purchaseRequest->threeDSAcctAuthValue = 'xgQYYgZVAAAAAAAAAAAAAAAAAAAA';

$purchaseRequest->softDescMarcTerminal = 'EPAYCO*RogertCastillo';// Para soft descriptor
$purchaseRequest->softDescFacilitatorId = '00000266029';
$purchaseRequest->softDescSalesOrgId = '00000266029';
$purchaseRequest->softDescSubMerchId = 627579;

//{"cabeceraSolicitud":{"infoPuntoInteraccion":{"idTerminal":"ESB20107","idAdquiriente":"11174653","idTransaccionTerminal":4805,"tipoTerminal":"WEB","modoCapturaPAN":"Manual",
//"capacidadPIN":"Virtual"}},"idPersona":{"tipoDocumento":"CC","numDocumento":"1067860923"},"infoMedioPago":{"idTarjetaCredito":{"franquicia":"MasterCard","numTarjeta":"5186000600001015",
//"fechaExpiracion":"2025-12-31","codVerificacion":""}},"infoCompra":{"montoTotal":59500,"infoImpuestos":{"tipoImpuesto":"IVA","monto":9500,"baseImpuesto":50000},"referencia":101626288,
//"cantidadCuotas":46,"infoFacilitador":{"marcTerminal":"EPAYCO*JorgeFlorezMart","FacilitadorID":"00000266029","SalesOrgID":"00000266029","SubMerchID":627579}},
//"infoAdicional":{"infoPago":{"indicadorPago":"UCOF","tipoPago":"0","tipoMontoRecurrente":""}}}

$response = $sdk->createTransaction($purchaseRequest);
$response = json_decode($response);
print_r($response);
