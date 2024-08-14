<?php
namespace Epayco\SdkRedeban\DTOs\Apisac;

class RefundDto
{
    public string $transactionDate;
    public string $acquirerId;
    public string $cardNumber;
    public float $totalAmount;
    public string $refundApprovalNumber;
    public ?float $adjustmentAmount;
    public function __construct()
    {
        $this->transactionDate = date("Y-m-d");
        $this->acquirerId = "";
        $this->cardNumber = "";
        $this->totalAmount = "";
        $this->refundApprovalNumber = "";
        $this->adjustmentAmount = null;
    }

}
