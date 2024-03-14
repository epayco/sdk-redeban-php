<?php 
namespace Epayco\SdkRedeban\dto;
class VoidDto{
    public $terminalType;
    public $terminalId;
    public $acquirerId;
    public $terminalTransactionId;
    public $panCaptureMode;
    public $pinCapability;
    public $brand;
    public $trackData;
    public $tokenData;
    public $tokenStatus;
    public $discreetData;
    public $totalAmount;
    public $amountTax;
    public $detailedAmountType;
    public $detailedAmount;
    public $reference;
    public $installmentCount;
    public $additionalDataType;
}
