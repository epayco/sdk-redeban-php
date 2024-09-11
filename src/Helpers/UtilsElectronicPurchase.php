<?php

namespace Epayco\SdkRedeban\Helpers;

trait UtilsElectronicPurchase
{
    private function cleanRequest(?string $maskedCard, ?string $cardType, ?object $request): ?object
    {
        if (!$request) {
            return null;
        }

        if (isset($request->infoMedioPago->{$cardType})) {
            $request->infoMedioPago->{$cardType}->numTarjeta = $maskedCard;
            $request->infoMedioPago->{$cardType}->fechaExpiracion = '';
            $request->infoMedioPago->{$cardType}->codVerificacion = '';
        }

        return $request;
    }

}
