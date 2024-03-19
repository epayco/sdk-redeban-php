<?php

namespace Epayco\SdkRedeban\Services;

use Epayco\SdkRedeban\Services\Service;
use Epayco\SdkRedeban\Repositories\RedebanRepository;

class ShopService extends Service
{
    public $outData = [];
    public function __invoke($data)
    {
        $obj        = json_decode(json_encode($data));
        $request    = $this->generateRequestShop($obj);

        $redebanRepository  = new RedebanRepository();
        $rest = $redebanRepository->shopRequest($request);

        $restFinalPos = [];
        $restPos                = $rest['soapenv:Body']['com:compraProcesarRespuesta'];
        $restFinalPos['cod']    = $restPos['com:infoRespuesta']['esb:codRespuesta'];
        $restFinalPos['date']   = $restPos['com:infoCompraResp']['com:fechaTransaccion'];
        $restFinalPos['descRes'] = $restPos['com:infoRespuesta']['esb:estado'];
        $restFinalPos['status'] = $restPos['com:infoRespuesta']['esb:descRespuesta'];
        $this->outData          = $restFinalPos;
        return true;
    }


    private function generateRequestShop($obj)
    {

        $arr = [
            'compraProcesarSolicitud' => [
                'cabeceraSolicitud' => [
                    'infoPuntoInteraccion' => [
                        'tipoTerminal' => $obj->terminalType,
                        'idTerminal' => $obj->terminalId,
                        'idAdquiriente' => $obj->acquirerId,
                        'idTransaccionTerminal' => $obj->terminalTransactionId,
                        'modoCapturaPAN' => $obj->panCaptureMode,
                        'capacidadPIN' => $obj->pinCapability,
                    ],
                ],
                'infoMedioPago' => [
                    'idTrack' => [
                        'Franquicia' => $obj->brand,
                        'track' => $obj->trackData,
                        'tipoCuenta' => $obj->accountType,
                    ],
                    'infoEMV' => [
                        'datosToken' => $obj->tokenData,
                        'datosDiscretos' => $obj->discreetData,
                        'estadoToken' => $obj->tokenStatus,
                    ],
                ],
                'infoCompra' => [
                    'montoTotal' => $obj->totalAmount,
                    'infoImpuestos' => [
                        ['tipoImpuesto' => "IVA", 'monto' => $obj->amountTax]
                    ],
                    'montoDetallado' => [
                        ['tipoMontoDetallado' => $obj->detailedAmountType, 'monto' => $obj->detailedAmount],
                    ],
                    'referencia' => $obj->reference,
                    'cantidadCuotas' => $obj->installmentCount,
                ],
                // 'datosAdicionales' => [
                //     ['tipo' => $obj->additionalData[0]->type, 'valor' => $obj->additionalData[0]->value],
                //     ['tipo' => $obj->additionalData[1]->type, 'valor' => $obj->additionalData[1]->value],
                // ],
            ],
        ];

        return json_decode(json_encode($arr)); //converter all array to object
    }
}