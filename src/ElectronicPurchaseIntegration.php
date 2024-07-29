<?php

namespace Epayco\SdkRedeban;

use Epayco\SdkRedeban\DTOs\Electronic\DataConfigSdkDto;
use Epayco\SdkRedeban\DTOs\Electronic\PurchaseDto;
use Epayco\SdkRedeban\Helpers\JsonResponse;
use Epayco\SdkRedeban\Helpers\PurchaseConfig;
use Epayco\SdkRedeban\Interfaces\Electronic\Integration;
use Epayco\SdkRedeban\Services\Electronic\PurchaseService;
use Epayco\SdkRedeban\Services\Electronic\ReverseService;
use Epayco\SdkRedeban\Validations\Electronic\PurchaseValidation;
use Epayco\SdkRedeban\Validations\Electronic\ReverseValidation;


class ElectronicPurchaseIntegration implements Integration
{
    use JsonResponse;
    private DataConfigSdkDto $sdkConfig;

    public function __construct()
    {
        $this->sdkConfig = new DataConfigSdkDto();
    }

    public function createTransaction(PurchaseDto $request,
                                      $validation = new PurchaseValidation,
                                      $service = new PurchaseService
    ): ?string {
        if ($validation($request)) {
            return $this->response($service($validation->response), $service->outData);
        }
        return $this->response($validation->response);
    }

    public function getTransaction(): string
    {
        return "Not implemented";
    }

    public function refundTransaction(): string
    {
        return "Not implemented";
    }

    public function undoTransaction(PurchaseDto $request,
                                    $validation = new ReverseValidation,
                                    $service = new ReverseService
    ): ?string {
        $validationResponse = $validation($request);
        if ($validationResponse) {
            return $this->response($service($validation->response), $service->outData);
        }
        return $this->response($validation->response);
    }

    public function setUsername($username): self
    {
        $this->sdkConfig->username = $username;
        return $this;
    }
    public function setPassword($password): self
    {
        $this->sdkConfig->password = $password;
        return $this;
    }
    public function setLocalCert($localCert): self
    {
        $this->sdkConfig->localCert = $localCert;
        return $this;
    }
    public function setLocalPrivateKey($localPrivateKey): self
    {
        $this->sdkConfig->localPrivateKey = $localPrivateKey;
        return $this;
    }
    public function setRedebanCert($redebanCert): self
    {
        $this->sdkConfig->redebanCert = $redebanCert;
        return $this;
    }

    public function setEnvironment($environment): self
    {
        $this->sdkConfig->environment = $environment;
        return $this;
    }

    public function build(): void
    {
        PurchaseConfig::getInstance()->setConfig($this->sdkConfig);
    }
}
