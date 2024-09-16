<?php

namespace Epayco\SdkRedeban\Helpers;
use Epayco\SdkRedeban\Helpers\SDKConfig;

trait UtilsPresentSales
{
    public function removeCardData($request): mixed
    {
        $sdkConfig = SDKConfig::getInstance();
        if ($sdkConfig->getConfig('environment') !== 'test') {
            unset($request->infoMedioPago->infoEMV);
            unset($request->infoMedioPago->idTrack);
            unset($request->datosAdicionales);
        }
        return $request;
    }
}
