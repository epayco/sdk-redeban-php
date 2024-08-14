<?php

namespace Epayco\SdkRedeban\Interfaces;

use Epayco\SdkRedeban\DTOs\Electronic\PurchaseDto;
use Epayco\SdkRedeban\Services\Electronic\ReverseService;
use Epayco\SdkRedeban\Validations\Electronic\PurchaseValidation;

interface Undo
{
    public function undoTransaction(PurchaseDto $request, PurchaseValidation $validation, ReverseService $service);
}
