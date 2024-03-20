<?php

namespace Epayco\SdkRedeban\Services;

use Epayco\SdkRedeban\Services\Service;
use Epayco\SdkRedeban\Repositories\RedebanRepository;

class VoidService extends Service
{
    public $outData = [];
    public $inData = [];
    public bool $status = false;

    public function __invoke($data)
    {
        $this->inData = $data;
        $this->process();
        return $this->status;
    }
    public function process()
    {
        $redebanRepository = new RedebanRepository();
        $rest = $redebanRepository->void($this->generateRequestVoid((object)$this->inData));
        $restPos=$rest['soapenv:Body']['com:compraCancelacionProcesarRespuesta'];
        $restFinalPos['cod']=$restPos['com:infoRespuesta']['esb:codRespuesta'];
        $restFinalPos['descRes']=$restPos['com:infoRespuesta']['esb:estado'];
        $restFinalPos['status']=$restPos['com:infoRespuesta']['esb:descRespuesta'];
        $this->outData=$restFinalPos;
        $this->status = true;
    }

    function generateRequestVoid($data)
    {
        $compraCancelacionProcesarSolicitud = [
            "cabeceraSolicitud" => [
                "infoPuntoInteraccion" => [
                    "tipoTerminal" => 'MPOS',
                    "idTerminal" => $data->terminalId,
                    "idAdquiriente" => $data->acquirerId,
                    "idTransaccionTerminal" => $data->terminalTransactionId,
                    "modoCapturaPAN" => $data->panCaptureMode,
                    "capacidadPIN" => $data->pinCapability,
                ]
            ],
            "infoMedioPago" => [
                "idTrack" => [
                    "Franquicia" => $data->franchise,
                    "track" => $data->track,
                    "tipoCuenta" => $data->accountType,
                ],
                "infoEMV" => [
                    "datosToken" => $data->tokenData,
                    "datosDiscretos" => $data->discreteData,
                    "estadoToken" => $data->tokenStatus,
                ],
            ],
            "infoCompra" => [
                "montoTotal" => $data->totalAmount,
                "infoImpuestos" => [
                    "tipoImpuesto" => "IVA",
                    "monto" => $data->taxAmount,
                    "baseImpuesto" => $data->amountBase,
                ],
                "montoDetallado" => [
                    "tipoMontoDetallado" => 'BaseDevolucionIVA',
                    "monto" => $data->VATRefundBase,
                ],
                "referencia" => $data->reference,
                "cantidadCuotas" => $data->installmentCount,
            ],
            "infoRefCancelacion" => [
                "numAprobacion" => 'approvalNumber',
                "idTransaccionAutorizador" => $data->authorizerTransactionId
            ],
        ];
        return (object) $compraCancelacionProcesarSolicitud;
    }

}
