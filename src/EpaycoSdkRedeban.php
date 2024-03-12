<?php

namespace Epayco\SdkRedeban;

use Epayco\SdkRedeban\dto\ProcessTransactionRedebanVentaPresente;
use Epayco\SdkRedeban\validations\ProcessTransactionValidation;
use Epayco\SdkRedeban\services\CompraService;
use Epayco\SdkRedeban\helpers\HelperResponse;

class EpaycoSdkRedeban extends HelperResponse
{
    public function __construct()
    {
        // Puedes realizar cualquier inicialización aquí
    }

    public function helloWorld()
    {
        return "¡Hola, mundo!";
    }

    public function processTransaction(ProcessTransactionRedebanVentaPresente $process, CompraService $compraService)
    { //comprar
        $data = ProcessTransactionValidation::validate($process);
        if ($data["success"]) {
            return $this->responseJson($compraService($process), $compraService->outData);
        }
        return $this->responseJson(false, $data);
    }

    public function cancelTransaction()
    { //anular

    }

    public function reverseTransaction()
    { //reverso

    }

    // Puedes agregar más métodos y funcionalidades aquí
}
