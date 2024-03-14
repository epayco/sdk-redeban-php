<?php 
namespace Epayco\SdkRedeban\dto;
class ShopDto{
    public  $reference;
    public  float $amount;
    public  string $terminalType;
    public  string $terminalId;
    public  string $acquirerId;
    public  string $terminalTransactionId;
    public  string $panCaptureMode;
    public  string $pinCapability;
    public  string $brand;
    public  string $trackData;
    public  string $tokenData;
    public  string $tokenStatus;
    public  string $discreetData;
    public  float $totalAmount;
    public  float $amountTax;
    public  string $detailedAmountType;
    public  float $detailedAmount;
    public  int $installmentCount;
    public  string $additionalDataType;
    public  string $additionalDataValue;
    
}