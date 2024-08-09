<?php

namespace Epayco\SdkRedeban\Interfaces\Electronic;

use Epayco\SdkRedeban\DTOs\Electronic\RefundDto;
use Epayco\SdkRedeban\DTOs\Electronic\PurchaseDto;
use Epayco\SdkRedeban\DTOs\Electronic\ShowTransactionDto;
use Epayco\SdkRedeban\Services\Electronic\RefundService;
use Epayco\SdkRedeban\Services\Electronic\PurchaseService;
use Epayco\SdkRedeban\Services\Electronic\ReverseService;
use Epayco\SdkRedeban\Validations\Electronic\RefundValidation;
use Epayco\SdkRedeban\Validations\Electronic\PurchaseValidation;

interface Integration
{
    public function createTransaction(PurchaseDto $request, PurchaseValidation $validation, PurchaseService $service);
    public function getTransaction(ShowTransactionDto $request);
    public function refundTransaction(RefundDto $request, RefundValidation $validation, RefundService $service);
    public function undoTransaction(PurchaseDto $request, PurchaseValidation $validation, ReverseService $service);
}
