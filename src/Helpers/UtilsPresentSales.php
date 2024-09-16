<?php

namespace Epayco\SdkRedeban\Helpers;
use Epayco\SdkRedeban\Helpers\PurchaseConfig;

trait UtilsPresentSales
{
    public function removeCardData($request): mixed
    {
        $sdkConfig = PurchaseConfig::getInstance();
        $ePurchaseConfig = $sdkConfig->getConfig();
        if ($ePurchaseConfig->environment !== 'test') {
            unset($request->infoMedioPago->infoEMV);
            unset($request->infoMedioPago->idTrack);
            unset($request->datosAdicionales);
        }
        return $request;
    }
}
