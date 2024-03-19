<?php
namespace Epayco\SdkRedeban\services;

use Epayco\SdkRedeban\services\Service;
use Epayco\SdkRedeban\repositories\RedebanRepository;

class ReverseService extends Service
{
    public $outData = [];
    public function __invoke($inputData)
    {
        $request    = $this->generateRequestReverse($inputData);

        $redebanRepository  = new RedebanRepository();
        $rest=$redebanRepository->reverse($request);

        $restFinalPos=[];
        $restPos                = $rest['soapenv:Body']['com:compraReversarRespuesta'];
        $restFinalPos['cod']    = $restPos['com:infoRespuesta']['esb:codRespuesta'];
        $restFinalPos['date']   = $restPos['com:infoCompraResp']['com:fechaTransaccion'];
        $restFinalPos['descRes']= $restPos['com:infoRespuesta']['esb:estado'];
        $restFinalPos['status'] = $restPos['com:infoRespuesta']['esb:descRespuesta'];
        $this->outData          = $restFinalPos;
        return true;
        
    }
    private function generateRequestReverse($obj){

        $arr= [
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
                                ['tipoImpuesto' => "IVA", 'monto' => $obj->taxInfo[0]['amount']]
                            ],
                            'montoDetallado' => [
                                ['tipoMontoDetallado' => $obj->detailedAmount[0]['detailedAmountType'], 'monto' => $obj->detailedAmount[0]['amount']],
                                ['tipoMontoDetallado' => $obj->detailedAmount[1]['detailedAmountType'], 'monto' => $obj->detailedAmount[1]['amount']],
                            ],
                            'referencia' => $obj->reference,
                            'cantidadCuotas' => $obj->qtyFee,
                        ],
                        'datosAdicionales' => [
                            ['tipo' => $obj->additionalData[0]['type'], 'valor' => $obj->additionalData[0]['value'],
                            ['tipo' => $obj->additionalData[1]['type'], 'valor' => $obj->additionalData[1]['value'],
                        ],
                    ],
                ]
        ]];

        return json_decode(json_encode($arr)); //converter all array to object

    }
}