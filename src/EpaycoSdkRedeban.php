<?php

namespace Epayco\SdkRedeban;

use Epayco\SdkRedeban\dto\ProcessTransactionRedebanVentaPresente;
use Epayco\SdkRedeban\validations\ProcessTransactionValidation;
use Epayco\SdkRedeban\helpers\HelperResponse;
use Epayco\SdkRedeban\dto\ShopDto;
use Epayco\SdkRedeban\services\ShopService;
use Epayco\SdkRedeban\validations\ShopValidation;
use Epayco\SdkRedeban\dto\VoidDto;
use Epayco\SdkRedeban\services\VoidService;
use Epayco\SdkRedeban\validations\VoidValidation;

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

    public function voidTransaction(VoidDto $request, $voidValidation = new VoidValidation, $voidService = new VoidService)
    { //anular
        if ($voidValidation($request)) {

            return $this->responseJson($voidService($voidValidation), $voidService->outData);
        }
        return $this->responseJson(false, $voidValidation->response);
    }

    public function reverseTransaction()
    { //reverso

    }

    // Puedes agregar más métodos y funcionalidades aquí
}
