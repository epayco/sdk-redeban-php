<?php

namespace Epayco\SdkRedeban;

use Epayco\SdkRedeban\DTOs\Electronic\DataConfigSdkDto;
use Epayco\SdkRedeban\DTOs\Electronic\PurchaseDto;
use Epayco\SdkRedeban\DTOs\Electronic\RefundDto;
use Epayco\SdkRedeban\DTOs\Electronic\ShowTransactionDto;
use Epayco\SdkRedeban\Helpers\JsonResponse;
use Epayco\SdkRedeban\Helpers\PurchaseConfig;
use Epayco\SdkRedeban\Interfaces\Purchase;
use Epayco\SdkRedeban\Interfaces\Refund;
use Epayco\SdkRedeban\Interfaces\Show;
use Epayco\SdkRedeban\Interfaces\Undo;
use Epayco\SdkRedeban\Services\Electronic\PurchaseService;
use Epayco\SdkRedeban\Services\Electronic\RefundService;
use Epayco\SdkRedeban\Services\Electronic\ReverseService;
use Epayco\SdkRedeban\Services\Electronic\ShowService;
use Epayco\SdkRedeban\Validations\Electronic\PurchaseValidation;
use Epayco\SdkRedeban\Validations\Electronic\RefundValidation;
use Epayco\SdkRedeban\Validations\Electronic\ShowValidation;

class ElectronicPurchaseIntegration implements Purchase, Show, Refund, Undo
{
    use JsonResponse;
    private DataConfigSdkDto $sdkConfig;

    public function __construct()
    {
        $this->sdkConfig = new DataConfigSdkDto();
    }

    public function createTransaction(?PurchaseDto $request,
                                                       $validation = new PurchaseValidation,
                                                       $service = new PurchaseService
    ): ?string {
        if ($validation($request)) {
            return $this->response(
                $service->purchase($validation->response),
                $service->outData,
                $service->logs
            );
        }
        return $this->response(false, $validation->response);
    }

    public function getTransaction(
        ?ShowTransactionDto $request,
        $validation = new ShowValidation,
        $service = new ShowService
    ): ?string
    {
        $validationResponse = $validation($request);
        if ($validationResponse) {
            return $this->response(
                $service->show($validation->response),
                $service->outData,
                $service->logs
            );
        }
        return $this->response(false, $validation->response);
    }

    public function refundTransaction(
        ?RefundDto $request,
        $validation = new RefundValidation,
        $service = new RefundService
    ): ?string {
        $validationResponse = $validation($request);
        if ($validationResponse) {
            return $this->response(
                $service->refund($validation->response),
                $service->outData,
                $service->logs
            );
        }
        return $this->response(false, $validation->response);
    }

    public function undoTransaction(?PurchaseDto $request,
                                    $validation = new PurchaseValidation,
                                    $service = new ReverseService
    ): ?string {
        $validationResponse = $validation($request);
        if ($validationResponse) {
            return $this->response(
                $service->reverse($validation->response),
                $service->outData,
                $service->logs
            );
        }
        return $this->response(false, $validation->response);
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
