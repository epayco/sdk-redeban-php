<?php

namespace Epayco\SdkRedeban\Interfaces;

use Epayco\SdkRedeban\DTOs\Apisac\RefundDto as ApisacRefundDto;
use Epayco\SdkRedeban\DTOs\Electronic\RefundDto as ElectronicRefundDto;
use Epayco\SdkRedeban\Services\Apisac\RefundService as ApisacRefundService;
use Epayco\SdkRedeban\Services\Electronic\RefundService as ElectronicRefundService;
use Epayco\SdkRedeban\Validations\Apisac\RefundValidation as ApisacRefundValidation;
use Epayco\SdkRedeban\Validations\Electronic\RefundValidation as ElectronicRefundValidation;

interface Refund
{
    public function refundTransaction(
        ApisacRefundDto | ElectronicRefundDto $request,
        ApisacRefundValidation | ElectronicRefundValidation $validation,
        ApisacRefundService | ElectronicRefundService $service
    );
}
