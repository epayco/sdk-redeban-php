<?php

namespace Epayco\SdkRedeban\Interfaces;

use Epayco\SdkRedeban\DTOs\Electronic\PurchaseDto;
use Epayco\SdkRedeban\Services\Electronic\PurchaseService;
use Epayco\SdkRedeban\Validations\Electronic\PurchaseValidation;

interface Purchase
{
    public function createTransaction(PurchaseDto $request, PurchaseValidation $validation, PurchaseService $service);
}
