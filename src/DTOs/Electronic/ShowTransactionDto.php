<?php
namespace Epayco\SdkRedeban\DTOs\Electronic;

class ShowTransactionDto
{
    public string $terminalType;
    public string $terminalId;
    public string $acquirerId;
    public string $terminalTransactionId;
    public string $transactionDate;
    public function __construct()
    {
        $this->terminalType = "";
        $this->terminalId = "";
        $this->acquirerId = "";
        $this->terminalTransactionId = "";
        $this->transactionDate = "";
    }

}
