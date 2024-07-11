<?php

namespace Epayco\SdkRedeban\Services;

use Epayco\SdkRedeban\Repositories\RedebanRepository;
use Exception;
use stdClass;

class ShopService extends Service
{
    public mixed $outData;
    public function __invoke($data): bool
    {
        $restFinalPos = [];
        $obj = json_decode(json_encode($data));
        $request = $this->generateRequestShop($obj);
        $redebanRepository = new RedebanRepository();
        try {
            $redebanResponse = $redebanRepository->purchase($request);
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

    public function generateRequestShop($obj): stdClass {
        $compraProcesarSolicitud = new stdClass();

        $compraProcesarSolicitud->cabeceraSolicitud = new stdClass();
        $compraProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion = new stdClass();
        $compraProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion->tipoTerminal = $obj->terminalType;
        $compraProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion->idTerminal = $obj->terminalId;
        $compraProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion->idAdquiriente = $obj->acquirerId;
        $compraProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion->idTransaccionTerminal = $obj->terminalTransactionId;
        $compraProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion->modoCapturaPAN = $obj->panCaptureMode;
        $compraProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion->capacidadPIN = $obj->pinCapability;

        $compraProcesarSolicitud->infoMedioPago = new stdClass();
        $compraProcesarSolicitud->infoMedioPago->idTrack = new stdClass();
        $compraProcesarSolicitud->infoMedioPago->idTrack->Franquicia = $obj->brand;
        $compraProcesarSolicitud->infoMedioPago->idTrack->track = $obj->trackData;
        $compraProcesarSolicitud->infoMedioPago->idTrack->tipoCuenta = $obj->accountType;

        $compraProcesarSolicitud->infoMedioPago->infoEMV = new stdClass();
        $compraProcesarSolicitud->infoMedioPago->infoEMV->datosToken = $obj->tokenData;
        $compraProcesarSolicitud->infoMedioPago->infoEMV->datosDiscretos = $obj->discreetData;
        $compraProcesarSolicitud->infoMedioPago->infoEMV->estadoToken = $obj->tokenStatus;

        $compraProcesarSolicitud->infoCompra = new stdClass();
        $compraProcesarSolicitud->infoCompra->montoTotal = $obj->totalAmount;
        $compraProcesarSolicitud->infoCompra->referencia = $obj->reference;
        $compraProcesarSolicitud->infoCompra->cantidadCuotas = $obj->instalmentCount;

        $compraProcesarSolicitud->infoCompra->infoImpuestos = array();
        foreach ($obj->infoTax as $value) {
            $values = new stdClass();
            $values->tipoImpuesto = $value->taxType;
            $values->monto = $value->amountTax;
            $compraProcesarSolicitud->infoCompra->infoImpuestos[] = $values;
        }

        $compraProcesarSolicitud->infoCompra->montoDetallado = array();
        foreach ($obj->detailedAmount as $value) {
            $values = new stdClass();
            $values->tipoMontoDetallado = $value->detailedAmountType;
            $values->monto = $value->detailedAmount;
            $compraProcesarSolicitud->infoCompra->montoDetallado[] = $values;
        }

        $compraProcesarSolicitud->datosAdicionales = array();
        foreach ($obj->additionalData as $value) {
            $values = new stdClass();
            $values->tipo = $value->type;
            $values->valor = $value->value;
            $compraProcesarSolicitud->datosAdicionales[] = $values;
        }

        return $compraProcesarSolicitud;
    }

}
