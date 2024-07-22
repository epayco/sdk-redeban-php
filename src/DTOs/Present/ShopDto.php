<?php
namespace Epayco\SdkRedeban\DTOs\Present;

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
    public int $instalmentCount;
    public mixed $additionalData;
    public mixed $infoTax;
    public mixed $detailedAmount;

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
        $this->instalmentCount = 0;
        $this->reference = "";
        $this->additionalData = [];
        $this->infoTax = [];
        $this->detailedAmount = [];
    }

}
