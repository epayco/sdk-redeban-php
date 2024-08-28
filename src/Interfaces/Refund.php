<?php

namespace Epayco\SdkRedeban\Interfaces;

use Epayco\SdkRedeban\Services\Electronic\RefundService;
use Epayco\SdkRedeban\Validations\Electronic\RefundValidation;

interface Refund
{
    public function refundTransaction(mixed $request, RefundValidation $validation, RefundService $service);
}
