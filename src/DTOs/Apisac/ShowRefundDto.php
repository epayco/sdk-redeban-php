<?php
namespace Epayco\SdkRedeban\DTOs\Apisac;

class ShowRefundDto
{
    public string $transactionDate;
    public string $acquirerId;
    public string $cardNumber;
    public float $totalAmount;
    public string $refundApprovalNumber;
    public string $gatewayId;
    public string $adjustmentId;
    public function __construct()
    {
        $this->transactionDate = "";
        $this->acquirerId = "";
        $this->cardNumber = "";
        $this->totalAmount = 0;
        $this->refundApprovalNumber = "";
        $this->gatewayId = "";
        $this->adjustmentId = "";
    }

}
