<?php

namespace Epayco\SdkRedeban\Services\Electronic;

use Epayco\SdkRedeban\Helpers\UtilsElectronicPurchase;
use Epayco\SdkRedeban\Repositories\PurchaseElectronicRepository;
use Epayco\SdkRedeban\Services\Service;
use Exception;
use stdClass;

class ShowService extends Service
{
    use UtilsElectronicPurchase;
    public mixed $outData;
    public mixed $logs;
    public function show($data): bool
    {
        $restFinalPos = [];
        $obj = json_decode(json_encode($data));
        $request = $this->generateShowRequest($obj);
        $redebanRepository = new PurchaseElectronicRepository();
        try {
            $showResponse = $redebanRepository->show($request);
            $providerResponse = $showResponse->providerResponse ?? null;
            $restFinalPos = (array)$providerResponse ?? [];

            $status = isset($providerResponse->infoRespuesta->codRespuesta) && $providerResponse->infoRespuesta->codRespuesta == '00';

            $showResponse->logs->request = $this->cleanRequest(null, null, $request);
        } catch(Exception $e) {
            $providerResponse = $e->getMessage();
            $status = false;
        }
        $this->logs = $showResponse->logs ?? $providerResponse ?? null;
        $this->outData = $restFinalPos;

        return $status;
    }

    public function generateShowRequest($obj): stdClass
    {
        $consultarEstadoDePagoSolicitud = new stdClass();

        $consultarEstadoDePagoSolicitud->cabeceraSolicitud = new stdClass();
        $consultarEstadoDePagoSolicitud->cabeceraSolicitud->infoPuntoInteraccion = new stdClass();
        $consultarEstadoDePagoSolicitud->cabeceraSolicitud->infoPuntoInteraccion->tipoTerminal = $obj->terminalType;
        $consultarEstadoDePagoSolicitud->cabeceraSolicitud->infoPuntoInteraccion->idTerminal = $obj->terminalId;
        $consultarEstadoDePagoSolicitud->cabeceraSolicitud->infoPuntoInteraccion->idAdquiriente = $obj->acquirerId;
        $consultarEstadoDePagoSolicitud->cabeceraSolicitud->infoPuntoInteraccion->idTransaccionTerminal = $obj->terminalTransactionId;
        $consultarEstadoDePagoSolicitud->fechaTransaccion = $obj->transactionDate;

        return $consultarEstadoDePagoSolicitud;
    }

}
