<?php

namespace Epayco\SdkRedeban\Interfaces;

use Epayco\SdkRedeban\DTOs\Electronic\ShowTransactionDto;

interface Show
{
    public function getTransaction(ShowTransactionDto $request);
}
