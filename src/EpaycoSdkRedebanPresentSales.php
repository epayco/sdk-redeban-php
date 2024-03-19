<?php

namespace Epayco\SdkRedeban;

use Epayco\SdkRedeban\Helpers\HelperResponse;
use Epayco\SdkRedeban\Helpers\DataConfigSdkRedeban;
use Epayco\SdkRedeban\DTOs\ShopDto;
use Epayco\SdkRedeban\Services\ShopService;
use Epayco\SdkRedeban\Validations\ShopValidation;
use Epayco\SdkRedeban\DTOs\VoidDto;
use Epayco\SdkRedeban\Services\VoidService;
use Epayco\SdkRedeban\Validations\VoidValidation;

class EpaycoSdkRedebanPresentSales extends HelperResponse
{
    private DataConfigSdkRedeban $dataConfigSdkRedeban;

    public function __construct()
    {
        $this->dataConfigSdkRedeban = new DataConfigSdkRedeban();
    }
    public function processTransaction(ShopDto $request, $shopValidation = new ShopValidation, $shopService = new ShopService)
    { //compra
        if ($shopValidation($request)) {
            return $this->responseJson($shopService($shopValidation->response), $shopService->outData);
        }
        return $this->responseJsonError($shopValidation->response);

    }

    public function cancelTransaction(VoidDto $request, $voidValidation = new VoidValidation, $voidService = new VoidService)
    { //anular
        if ($voidValidation($request)) {
            return $this->responseJson($voidService($voidValidation->response), $voidService->outData);
        }
        return $this->responseJson(false, $voidValidation->response);
    }

    public function reverseTransaction()
    { //reverso

    }
    public function setUsername($username)
    {
        $this->dataConfigSdkRedeban->username = $username;
        return $this;
    }
    public function setPassword($password)
    {
        $this->dataConfigSdkRedeban->password = $password;
        return $this;
    }
    public function setLocalPublicKey($localCert)
    {
        $this->dataConfigSdkRedeban->localCert = $localCert;
        return $this;
    }
    public function setLocalPrivateKey($localPrivateKey)
    {
        $this->dataConfigSdkRedeban->localPrivateKey = $localPrivateKey;
        return $this;
    }
    public function setPublicKey($publicKey)
    {
        $this->dataConfigSdkRedeban->publicKey = $publicKey;
        return $this;
    }
}
