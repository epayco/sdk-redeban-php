<?php

namespace Epayco\SdkRedeban\Interfaces\Electronic;

use Epayco\SdkRedeban\DTOs\Electronic\PurchaseDto;
use Epayco\SdkRedeban\Services\Electronic\PurchaseService;
use Epayco\SdkRedeban\Services\Electronic\ReverseService;
use Epayco\SdkRedeban\Validations\Electronic\PurchaseValidation;
use Epayco\SdkRedeban\Validations\Electronic\ReverseValidation;

interface Integration
{
    public function createTransaction(PurchaseDto $request, PurchaseValidation $validation, PurchaseService $service);
    public function getTransaction();
    public function refundTransaction();
    public function undoTransaction(PurchaseDto $request, ReverseValidation $validation, ReverseService $service);
}
