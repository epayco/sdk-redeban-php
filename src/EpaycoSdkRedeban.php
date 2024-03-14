<?php

namespace Epayco\SdkRedeban;

use Epayco\SdkRedeban\dto\ProcessTransactionRedebanVentaPresente;
use Epayco\SdkRedeban\validations\ProcessTransactionValidation;
use Epayco\SdkRedeban\services\CompraService;
use Epayco\SdkRedeban\helpers\HelperResponse;
use Epayco\SdkRedeban\dto\ShopDto;
use Epayco\SdkRedeban\services\ShopService;
use Epayco\SdkRedeban\validations\ShopValidation;

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

    public function processTransaction(ShopDto $request, $shopValidation = new ShopValidation, $shopService = new ShopService)
    { 
        if($shopValidation($request)) {
            return $this->responseJson($shopService($shopValidation->response),$shopService->outData);
        }
        return $this->responseJsonError($shopValidation->response);

    }

    public function cancelTransaction()
    { //anular

    }

    public function reverseTransaction()
    { //reverso

    }

    // Puedes agregar más métodos y funcionalidades aquí
}
