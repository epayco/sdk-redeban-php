<?php

namespace Epayco\SdkRedeban\services;

use Epayco\SdkRedeban\services\Service;
use Epayco\SdkRedeban\repositories\RedebanRepository;

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
                        'tipoTerminal' => $obj->interactionPointInfo->terminalType,
                        'idTerminal' => $obj->interactionPointInfo->terminalId,
                        'idAdquiriente' => $obj->interactionPointInfo->acquirerId,
                        'idTransaccionTerminal' => $obj->interactionPointInfo->terminalTransactionId,
                        'modoCapturaPAN' => $obj->interactionPointInfo->panCaptureMode,
                        'capacidadPIN' => $obj->interactionPointInfo->pinCapability,
                    ],
                ],
                'infoMedioPago' => [
                    'idTrack' => [
                        'Franquicia' => $obj->paymentMethodInfo->trackId->brand,
                        'track' => $obj->paymentMethodInfo->trackId->trackData,
                        'tipoCuenta' => $obj->paymentMethodInfo->trackId->accountType,
                    ],
                    'infoEMV' => [
                        'datosToken' => $obj->paymentMethodInfo->emvInfo->tokenData,
                        'datosDiscretos' => $obj->paymentMethodInfo->emvInfo->discreetData,
                        'estadoToken' => $obj->paymentMethodInfo->emvInfo->tokenStatus,
                    ],
                ],
                'infoCompra' => [
                    'montoTotal' => $obj->purchaseInfo->totalAmount,
                    'infoImpuestos' => [
                        ['tipoImpuesto' => "IVA", 'monto' => $obj->purchaseInfo->taxInfo[0]->amount]
                    ],
                    'montoDetallado' => [
                        ['tipoMontoDetallado' => $obj->purchaseInfo->detailedAmount[0]->detailedAmountType, 'monto' => $obj->purchaseInfo->detailedAmount[0]->amount],
                        ['tipoMontoDetallado' => $obj->purchaseInfo->detailedAmount[1]->detailedAmountType, 'monto' => $obj->purchaseInfo->detailedAmount[1]->amount],
                    ],
                    'referencia' => $obj->purchaseInfo->reference,
                    'cantidadCuotas' => $obj->purchaseInfo->installmentCount,
                ],
                'datosAdicionales' => [
                    ['tipo' => $obj->additionalData[0]->type, 'valor' => $obj->additionalData[0]->value],
                    ['tipo' => $obj->additionalData[1]->type, 'valor' => $obj->additionalData[1]->value],
                ],
            ],
        ];

        return json_decode(json_encode($arr)); //converter all array to object

    }
}
