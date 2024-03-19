<?php
namespace Epayco\SdkRedeban\DTOs;

class ShopDto
{
    public string $reference;
    public float $amount;
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
    public string $tokenStatus;
    public string $discreetData;
    public float $totalAmount;
    public float $amountTax;
    public string $detailedAmountType;
    public float $detailedAmount;
    public int $installmentCount;
    public string $additionalDataType;

    public function __construct()
    {
        $this->terminalType = "";
        $this->terminalId = "";
        $this->acquirerId = "";
        $this->terminalTransactionId = "";
        $this->panCaptureMode = "";
        $this->pinCapability = "";
        $this->brand = "";
        $this->trackData = "";
        $this->tokenData = "";
        $this->accountType = "";
        $this->tokenStatus = "";
        $this->discreetData = "";
        $this->totalAmount = 0;
        $this->amountTax = 0;
        $this->detailedAmountType = "";
        $this->detailedAmount = 0;
        $this->installmentCount = 0;
        $this->reference = "";
        $this->additionalDataType = "";
    }

}