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
    private DataConfigSdkDto $dataConfigSdkRedeban;

    public function __construct()
    {
        $this->dataConfigSdkRedeban = new DataConfigSdkDto();
    }

    public function createTransaction(PurchaseDto $request,
                                      $validation = new PurchaseValidation,
                                      $service = new PurchaseService
    ): ?string {
        if ($validation($request)) {
            return JsonResponse::response($service($validation->response), $service->outData);
        }
        return JsonResponse::errorResponse($validation->response);
    }

    public function getTransaction()
    {
        // TODO: Implement getTransaction() method.
    }

    public function refundTransaction()
    {
        // TODO: Implement refundTransaction() method.
    }

    public function undoTransaction(PurchaseDto $request,
                                    $validation = new ReverseValidation,
                                    $service = new ReverseService
    ): ?string {
        if ($validation($request)) {
            return JsonResponse::response($service($validation->response), $service->outData);
        }
        return JsonResponse::errorResponse($validation->response);
    }

    public function setUsername($username): self
    {
        $this->dataConfigSdkRedeban->username = $username;
        return $this;
    }
    public function setPassword($password): self
    {
        $this->dataConfigSdkRedeban->password = $password;
        return $this;
    }
    public function setLocalCert($localCert): self
    {
        $this->dataConfigSdkRedeban->localCert = $localCert;
        return $this;
    }
    public function setLocalPrivateKey($localPrivateKey): self
    {
        $this->dataConfigSdkRedeban->localPrivateKey = $localPrivateKey;
        return $this;
    }
    public function setRedebanCert($redebanCert): self
    {
        $this->dataConfigSdkRedeban->redebanCert = $redebanCert;
        return $this;
    }

    public function setEnvironment($environment): self
    {
        $this->dataConfigSdkRedeban->environment = $environment;
        return $this;
    }

    public function build(): void
    {
        PurchaseConfig::getInstance()->setConfig($this->dataConfigSdkRedeban);
    }
}
