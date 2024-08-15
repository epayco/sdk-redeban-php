<?php

namespace Epayco\SdkRedeban\Helpers;

trait UtilsPresentSales
{
    public function removeCardData($request): mixed
    {
        unset($request->infoMedioPago->infoEMV);
        unset($request->infoMedioPago->idTrack);
        unset($request->datosAdicionales);
        return $request;
    }
}
