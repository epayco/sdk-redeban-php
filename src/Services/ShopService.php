<?php

namespace Epayco\SdkRedeban\Services;

use Epayco\SdkRedeban\Repositories\RedebanRepository;
use Exception;
use stdClass;

class ShopService extends Service
{
    public mixed $outData;
    public function __invoke($data)
    {
        $restFinalPos = [];
        $obj = json_decode(json_encode($data));
        $request = $this->generateRequestShop($obj);
        try {
            $redebanRepository = new RedebanRepository();
            $redebanResponse = $redebanRepository->shopRequest($request);
            $restFinalPos = (array)$redebanResponse;
            $status = isset($redebanResponse->infoRespuesta->codRespuesta) && $redebanResponse->infoRespuesta->codRespuesta == '00';
        } catch(Exception $e){
            $redebanResponse = $e;
            $status = false;
        }
        $restFinalPos['log_request']    = $request;
        $restFinalPos['log_response']   = $redebanResponse ?? null;
        $this->outData = $restFinalPos;
        return $status;
    }


    private function generateRequestShop($obj): stdClass
    {
        $compraProcesarSolicitud = new stdClass();
        $compraProcesarSolicitud->cabeceraSolicitud = new stdClass();
        $compraProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion = new stdClass();
        $compraProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion->tipoTerminal = $obj->terminalType;
        $compraProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion->idTerminal = $obj->terminalId;
        $compraProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion->idAdquiriente = $obj->acquirerId;
        //TODO: idTransaccionTerminal se suma en cada transaction
//        $trxIdTerminal = $configSitio->id_tran_terminal ?? 1;
        $compraProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion
            ->idTransaccionTerminal = $obj->terminalTransactionId;
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
        $compraProcesarSolicitud->infoCompra->referencia = $obj->reference; // ref payco
        $compraProcesarSolicitud->infoCompra->cantidadCuotas = $obj->installmentCount;

        $compraProcesarSolicitud->infoCompra->infoImpuestos = new stdClass();
        $compraProcesarSolicitud->infoCompra->infoImpuestos->tipoImpuesto = $obj->taxType;
        $compraProcesarSolicitud->infoCompra->infoImpuestos->monto = $obj->amountTax;

        $compraProcesarSolicitud->infoCompra->montoDetallado = new stdClass();
        $compraProcesarSolicitud->infoCompra->montoDetallado->tipoMontoDetallado = $obj->detailedAmountType;
        $compraProcesarSolicitud->infoCompra->montoDetallado->monto = $obj->detailedAmount;

        $compraProcesarSolicitud->datosAdicionales = new stdClass();
        $compraProcesarSolicitud->datosAdicionales->tipo = 'C4';
        $compraProcesarSolicitud->datosAdicionales->valor = '';

        return $compraProcesarSolicitud;
    }
}
