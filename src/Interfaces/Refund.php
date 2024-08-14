<?php

namespace Epayco\SdkRedeban\Interfaces;

use Epayco\SdkRedeban\DTOs\Electronic\RefundDto;
use Epayco\SdkRedeban\Services\Electronic\RefundService;
use Epayco\SdkRedeban\Validations\Electronic\RefundValidation;

interface Refund
{
    public function refundTransaction(RefundDto $request, RefundValidation $validation, RefundService $service);
}
