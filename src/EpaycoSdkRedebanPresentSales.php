<?php

namespace Epayco\SdkRedeban;

use Epayco\SdkRedeban\DTOs\Present\DataConfigSdkDto;
use Epayco\SdkRedeban\DTOs\Present\ShopDto;
use Epayco\SdkRedeban\DTOs\Present\VoidDto;
use Epayco\SdkRedeban\Helpers\HelperResponse;
use Epayco\SdkRedeban\Helpers\SDKConfig;
use Epayco\SdkRedeban\Services\Present\ReverseService;
use Epayco\SdkRedeban\Services\Present\ShopService;
use Epayco\SdkRedeban\Services\Present\VoidService;
use Epayco\SdkRedeban\Validations\Present\ShopValidation;
use Epayco\SdkRedeban\Validations\Present\VoidValidation;

class EpaycoSdkRedebanPresentSales extends HelperResponse
{
    private DataConfigSdkDto $dataConfigSdkRedeban;

    public function __construct()
    {
        $this->dataConfigSdkRedeban = new DataConfigSdkDto();
    }

    public function processTransaction(
        ShopDto $request,
        $shopValidation = new ShopValidation,
        $shopService = new ShopService
    ): array {
        if ($shopValidation($request)) {
            return $this->response($shopService($shopValidation->response), $shopService->outData);
        }
        return $this->responseError($shopValidation->response);

    }

    public function cancelTransaction(VoidDto $request, $voidValidation = new VoidValidation, $voidService = new VoidService)
    { //anular
        if ($voidValidation($request)) {
            return $this->response($voidService($voidValidation->response), $voidService->outData);
        }
        return $this->response(false, $voidValidation->response);
    }

    public function reverseTransaction(
        ShopDto $request,
        $reverseValidation = new ShopValidation,
        $reverseService = new ReverseService
    ): array {
        if ($reverseValidation($request)) {
            return $this->response($reverseService($reverseValidation->response), $reverseService->outData);
        }
        return $this->responseError($reverseValidation->response);

    }
    public function setUsername($username)
    {
        $this->dataConfigSdkRedeban->username = $username;
        SDKConfig::getInstance()->setConfig('username', $username);
        return $this;
    }
    public function setPassword($password)
    {
        $this->dataConfigSdkRedeban->password = $password;
        SDKConfig::getInstance()->setConfig('password', $password);
        return $this;
    }
    public function setLocalCert($localCert)
    {
        $this->dataConfigSdkRedeban->localCert = $localCert;
        SDKConfig::getInstance()->setConfig('localCert', $localCert);
        return $this;
    }
    public function setLocalPrivateKey($localPrivateKey)
    {
        $this->dataConfigSdkRedeban->localPrivateKey = $localPrivateKey;
        SDKConfig::getInstance()->setConfig('localPrivateKey', $localPrivateKey);
        return $this;
    }
    public function setRedebanCert($redebanCert)
    {
        $this->dataConfigSdkRedeban->redebanCert = $redebanCert;
        SDKConfig::getInstance()->setConfig('redebanCert', $redebanCert);
        return $this;
    }

    public function setEnvironment($environment)
    {
        $this->dataConfigSdkRedeban->environment = $environment;
        SDKConfig::getInstance()->setConfig('environment', $environment);
        return $this;
    }
}
