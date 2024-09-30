<?php

namespace Epayco\SdkRedeban\Interfaces;

use Epayco\SdkRedeban\DTOs\Apisac\ShowRefundDto;
use Epayco\SdkRedeban\Services\Apisac\ShowRefundService;
use Epayco\SdkRedeban\Validations\Apisac\ShowRefundValidation;

interface ShowRefund
{
    public function showRefund(
        ShowRefundDto $request,
        ShowRefundValidation $validation,
        ShowRefundService $service
    );
}
