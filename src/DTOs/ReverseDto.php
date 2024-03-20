<?php 
namespace Epayco\SdkRedeban\DTOs;
class ReverseDto{
    public string $terminalType;
    public string $terminalId;
    public string $acquirerId;
    public string $terminalTransactionId;
    public string $panCaptureMode;
    public string $pinCapability;
    public string $brand;
    public string $trackData;
    public string $accountType;
    public string $tokenData;
    public string $discreetData;
    public string $tokenStatus;
    public float $totalAmount;
    public array $taxInfo;
    public array $detailedAmount;
    public string $reference;
    public int $qtyFee;
    public array $additionalData;
}