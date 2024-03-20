<?php
namespace Epayco\SdkRedeban\DTOs;

class VoidDto
{

    public string $terminalId;
    public string $acquirerId;
    public string $terminalTransactionId;
    public string $panCaptureMode;
    public string $pinCapability;
    public string $franchise;
    public string $track;
    public string $accountType;
    public string $tokenData;
    public string $tokenStatus;
    public string $discreteData;
    public float $totalAmount;
    public float $taxAmount;
    public float $amountBase;
    public float $VATRefundBase;
    public string $reference;
    public int $installmentCount;
    public string $authorizerTransactionId;

    public function __construct()
    {
        $this->terminalId = "";
        $this->acquirerId = "";
        $this->terminalTransactionId = "";
        $this->panCaptureMode = "";
        $this->pinCapability = "";
        $this->franchise = "";
        $this->track = "";
        $this->accountType = "";
        $this->tokenData = "";
        $this->tokenStatus = "";
        $this->discreteData = "";
        $this->totalAmount = 0;
        $this->taxAmount = 0;
        $this->amountBase = 0;
        $this->VATRefundBase = 0;
        $this->reference = "";
        $this->installmentCount = 0;
        $this->authorizerTransactionId = "";
    }
}
