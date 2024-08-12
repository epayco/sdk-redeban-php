<?php
namespace Epayco\SdkRedeban\DTOs\Electronic;

class ReverseDto
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
    public string $paymentType;
    public string $recurringAmountType;
    public string $cardType; // idTarjetaCredito idTarjetaDebito
    public string $franchise;
    public string $cardNumber;
    public string $expirationDate;
    public ?string $securityCode;
    public ?string $personDocumentType;
    public ?string $personDocumentNumber;
    public ?string $threeDSEci;
    public ?string $threeDSDirectoryServerTransactionId;
    public ?string $threeDSSecVersion;
    public ?string $threeDSAcctAuthValue;
    public ?string $softDescMarcTerminal;
    public ?string $softDescFacilitatorId;
    public ?string $softDescSalesOrgId;
    public ?string $softDescSubMerchId;
    public ?string $infoPersonAddress;
    public ?string $infoPersonCity;
    public ?string $infoPersonDepartment;
    public ?string $infoPersonCommerceEmail;
    public ?string $infoPersonPhone;
    public ?string $infoPersonCellphone;
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
        $this->paymentType = "";
        $this->recurringAmountType = "";
        $this->cardType = "";
        $this->franchise = "";
        $this->cardNumber = "";
        $this->expirationDate = "";
        $this->securityCode = null;
        $this->personDocumentType = null;
        $this->personDocumentNumber = null;
        $this->threeDSEci = null;
        $this->threeDSDirectoryServerTransactionId = null;
        $this->threeDSSecVersion = null;
        $this->threeDSAcctAuthValue = null;
        $this->softDescMarcTerminal = null;
        $this->softDescFacilitatorId = null;
        $this->softDescSalesOrgId = null;
        $this->softDescSubMerchId = null;
        $this->infoPersonAddress = null;
        $this->infoPersonCity = null;
        $this->infoPersonDepartment = null;
        $this->infoPersonCommerceEmail = null;
        $this->infoPersonPhone = null;
        $this->infoPersonCellphone = null;
    }

}
