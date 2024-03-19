<?php
require '../vendor/autoload.php';

use Epayco\SdkRedeban\EpaycoSdkRedeban;

use Epayco\SdkRedeban\DTOs\ReverseDto;

$sdk = new EpaycoSdkRedeban();

$reverseDto=new ReverseDto();

$reverseDto->terminalType          = "MPOS";
$reverseDto->terminalId            = "ESB10001";
$reverseDto->acquirerId            = "0010203040";
$reverseDto->terminalTransactionId = "999998";
$reverseDto->panCaptureMode        = "CHIP";
$reverseDto->pinCapability         = "Permitido";
$reverseDto->brand                 = "MasterCard";
$reverseDto->trackData             = "XXXXXXXXXXXXXXXX=XXXXXXXXXXXXXXX";
$reverseDto->accountType           = "Credit";
$reverseDto->tokenData             = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
$reverseDto->discreetData          = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
$reverseDto->tokenStatus           = "XXXXXXXXXXXXXX XX";
$reverseDto->totalAmount           = 500;
$reverseDto->reference             = "Ref_123";
$reverseDto->qtyFee                = 10;

$reverseDto->taxInfo=
[
    [
        "amount"=> 48
    ]
];
$reverseDto->detailedAmount=
[
    [
        "detailedAmountType"=> "BaseVATRefund",
        "amount"=> 45
    ],
    [
        "detailedAmountType"=> "Tip",
        "amount"=> 20
    ]
];

$reverseDto->additionalData=[
    [
        "type"=> "C4",
        "value"=> "XXXXXXXXXXXX"
    ],
    [
        "type"=> "CH",
        "value"=> "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"
    ]
];

print_r($sdk->reverseTransaction($reverseDto));
