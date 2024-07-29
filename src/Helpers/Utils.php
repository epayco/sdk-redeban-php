<?php

namespace Epayco\SdkRedeban\Helpers;

trait Utils
{
    public function removeCardData($request): mixed
    {
        unset($request->infoMedioPago);
        return $request;
    }
}
