<?php

namespace Epayco\SdkRedeban;

use Epayco\SdkRedeban\dto\ProcessTransactionRedebanVentaPresente;
use Epayco\SdkRedeban\DTOs\ReverseDto;
use Epayco\SdkRedeban\validations\ProcessTransactionValidation;
use Epayco\SdkRedeban\services\CompraService;
use Epayco\SdkRedeban\helpers\HelperResponse;
use Epayco\SdkRedeban\dto\ShopDto;
use Epayco\SdkRedeban\services\ReverseService;
use Epayco\SdkRedeban\services\ShopService;
use Epayco\SdkRedeban\validations\ReverseValidation;
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

    public function shopTransaction(ShopDto $request, $shopValidation = new ShopValidation, $shopService = new ShopService)
    { 
        if($shopValidation($request)) {
            return $this->responseJson($shopService($shopValidation->response),$shopService->outData);
        }
        return $this->responseJsonError($shopValidation->response);

    }

    public function cancelTransaction()
    { //anular

    }

    public function reverseTransaction(ReverseDto $request, $reverseValidation = new ReverseValidation, $reverseService = new ReverseService)
    { 
        if($reverseValidation($request)) {
            return $this->responseJson($reverseService($reverseValidation->response),$reverseService->outData);
        }
        return $this->responseJsonError($reverseValidation->response);

    }

    // Puedes agregar más métodos y funcionalidades aquí
}
