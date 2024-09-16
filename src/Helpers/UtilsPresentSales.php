<?php

namespace Epayco\SdkRedeban\Helpers;
use Epayco\SdkRedeban\DTOs\Apisac\DataConfigSdkDto;

trait UtilsPresentSales
{
    public function removeCardData($request): mixed
    {
        $this->sdkConfig = new DataConfigSdkDto();
        if ($this->sdkConfig->environment !== 'test') {
            unset($request->infoMedioPago->infoEMV);
            unset($request->infoMedioPago->idTrack);
            unset($request->datosAdicionales);
        }
        return $request;
    }
}
