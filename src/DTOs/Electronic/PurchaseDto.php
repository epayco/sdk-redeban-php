<?php
namespace Epayco\SdkRedeban\DTOs\Electronic;

class PurchaseDto
{
    public string $terminalType;
    public string $terminalId;
    public string $acquirerId;
    public string $terminalTransactionId;
    public string $panCaptureMode;
    public string $pinCapability;
    public float $totalAmount;
    public float $ivaTax;
    public float $baseTax;
    public string $reference;
    public string $instalmentsQuantity;
    public string $paymentIndicator;
    public string $recurringAmountType;
    public string $paymentAccountType; // idTarjetaCredito idTarjetaDebito
    public string $franchise;
    public string $cardNumber;
    public string $expirationDate;
    public ?string $securityCode;
    public ?string $personDocumentType;
    public ?string $personDocumentNumber;
    public ?string $additionalInfoEci;
    public ?string $directoryServerTransactionId;
    public ?string $secVersion;
    public ?string $acctAuthValue;
    public ?string $marcTerminal;
    public ?string $facilitatorId;
    public ?string $salesOrgId;
    public ?string $subMerchId;
    public function __construct()
    {
        $this->terminalType = "";
        $this->terminalId = "";
        $this->acquirerId = "";
        $this->terminalTransactionId = "";
        $this->panCaptureMode = "";
        $this->pinCapability = "";
        $this->totalAmount = 0;
        $this->ivaTax = 0;
        $this->baseTax = 0;
        $this->reference = "";
        $this->instalmentsQuantity = "";
        $this->paymentIndicator = "";
        $this->recurringAmountType = "";
        $this->paymentAccountType = "";
        $this->franchise = "";
        $this->cardNumber = "";
        $this->expirationDate = "";
        $this->securityCode = null;
        $this->personDocumentType = null;
        $this->personDocumentNumber = null;
        $this->additionalInfoEci = null;
        $this->directoryServerTransactionId = null;
        $this->secVersion = null;
        $this->acctAuthValue = null;
        $this->marcTerminal = null;
        $this->facilitatorId = null;
        $this->salesOrgId = null;
        $this->subMerchId = null;
    }

}
