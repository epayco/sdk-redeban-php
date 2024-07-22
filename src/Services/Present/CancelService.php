<?php

namespace Epayco\SdkRedeban\Services\Present;

use Epayco\SdkRedeban\Repositories\RedebanRepository;
use Epayco\SdkRedeban\Services\Service;
use Exception;
use stdClass;

class CancelService extends Service
{
    public mixed $outData;
    public function __invoke($data): bool
    {
        $restFinalPos = [];
        $obj = json_decode(json_encode($data));
        $request = $this->generateCancelRequest($obj);
        $redebanRepository = new RedebanRepository();
        try {
            $redebanResponse = $redebanRepository->cancel($request);
            $restFinalPos = (array)$redebanResponse ?? [];

            $status = isset($redebanResponse->infoRespuesta->codRespuesta) && $redebanResponse->infoRespuesta->codRespuesta == '00';
        } catch(Exception $e) {
            $redebanResponse = $e;
            $status = false;
        }
        $restFinalPos['log_request']    = $request;
        $restFinalPos['log_response']   = $redebanResponse ?? null;
        $this->outData = $restFinalPos;

        return $status;
    }

    public function generateCancelRequest($request): stdClass {
        $compraProcesarSolicitud = new stdClass();

        $compraProcesarSolicitud->cabeceraSolicitud = new stdClass();
        $compraProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion = new stdClass();
        $compraProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion->tipoTerminal = $request->terminalType;
        $compraProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion->idTerminal = $request->terminalId;
        $compraProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion->idAdquiriente = $request->acquirerId;
        $compraProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion->idTransaccionTerminal = $request->terminalTransactionId;
        $compraProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion->modoCapturaPAN = $request->panCaptureMode;
        $compraProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion->capacidadPIN = $request->pinCapability;

        $compraProcesarSolicitud->infoMedioPago = new stdClass();
        $compraProcesarSolicitud->infoMedioPago->idTrack = new stdClass();
        $compraProcesarSolicitud->infoMedioPago->idTrack->Franquicia = $request->brand;
        $compraProcesarSolicitud->infoMedioPago->idTrack->track = $request->trackData;
        $compraProcesarSolicitud->infoMedioPago->idTrack->tipoCuenta = $request->accountType;

        $compraProcesarSolicitud->infoMedioPago->infoEMV = new stdClass();
        $compraProcesarSolicitud->infoMedioPago->infoEMV->datosToken = $request->tokenData;
        $compraProcesarSolicitud->infoMedioPago->infoEMV->datosDiscretos = $request->discreetData;
        $compraProcesarSolicitud->infoMedioPago->infoEMV->estadoToken = $request->tokenStatus;

        $compraProcesarSolicitud->infoCompra = new stdClass();
        $compraProcesarSolicitud->infoCompra->montoTotal = $request->totalAmount;
        $compraProcesarSolicitud->infoCompra->referencia = $request->reference;
        $compraProcesarSolicitud->infoCompra->cantidadCuotas = $request->instalmentCount;

        $compraProcesarSolicitud->infoRefCancelacion = new stdClass();
        $compraProcesarSolicitud->infoRefCancelacion->numAprobacion = $request->approvalNumber;
        $compraProcesarSolicitud->infoRefCancelacion->idTransaccionAutorizador = $request->transactionIdAuthorizer;

        $compraProcesarSolicitud->infoCompra->infoImpuestos = array();
        foreach ($request->infoTax as $value) {
            $values = new stdClass();
            $values->tipoImpuesto = $value->taxType;
            $values->monto = $value->amountTax;
            $compraProcesarSolicitud->infoCompra->infoImpuestos[] = $values;
        }

        $compraProcesarSolicitud->infoCompra->montoDetallado = array();
        foreach ($request->detailedAmount as $value) {
            $values = new stdClass();
            $values->tipoMontoDetallado = $value->detailedAmountType;
            $values->monto = $value->detailedAmount;
            $compraProcesarSolicitud->infoCompra->montoDetallado[] = $values;
        }

        $compraProcesarSolicitud->datosAdicionales = array();
        foreach ($request->additionalData as $value) {
            $values = new stdClass();
            $values->tipo = $value->type;
            $values->valor = $value->value;
            $compraProcesarSolicitud->datosAdicionales[] = $values;
        }

        return $compraProcesarSolicitud;
    }

}
