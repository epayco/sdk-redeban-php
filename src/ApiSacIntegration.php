<?php

namespace Epayco\SdkRedeban;

use Epayco\SdkRedeban\DTOs\Apisac\DataConfigSdkDto;
use Epayco\SdkRedeban\DTOs\Apisac\RefundDto;
use Epayco\SdkRedeban\DTOs\Apisac\ShowRefundDto;
use Epayco\SdkRedeban\Helpers\Apisac\ApiSacConfig;
use Epayco\SdkRedeban\Helpers\JsonResponse;
use Epayco\SdkRedeban\Interfaces\Refund;
use Epayco\SdkRedeban\Interfaces\ShowRefund;
use Epayco\SdkRedeban\Services\Apisac\RefundService;
use Epayco\SdkRedeban\Services\Apisac\ShowRefundService;
use Epayco\SdkRedeban\Validations\Apisac\RefundValidation;
use Epayco\SdkRedeban\Validations\Apisac\ShowRefundValidation;

class ApiSacIntegration implements Refund, ShowRefund
{
    use JsonResponse;
    private DataConfigSdkDto $sdkConfig;

    public function __construct()
    {
        $this->sdkConfig = new DataConfigSdkDto();
    }

    public function refundTransaction($request,
                   $validation = new RefundValidation,
                   $service = new RefundService
    ): ?string {
        if (!$request instanceof RefundDto) {
            return $this->response(false, "Datos de entrada incorrectos", null, null, 400);
        }
        $validationResponse = $validation($request);
        if ($validationResponse) {
            return $this->response(
                $service->refund($validation->response),
                null,
                $service->outData,
                $service->logs,
                $service->statusCode
            );
        }
        return $this->response(false, $validation->response, null, null, 400);
    }

    public function showRefund(
        ShowRefundDto $request,
        $validation = new ShowRefundValidation,
        $service = new ShowRefundService
    ): ?string {
        $validationResponse = $validation($request);
        if ($validationResponse) {
            return $this->response(
                $service->showRefund($validation->response),
                null,
                $service->outData,
                $service->logs,
                $service->statusCode
            );
        }
        return $this->response(false, $validation->response, null, null, 400);
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
    public function setLocalKey($localKey): self
    {
        $this->sdkConfig->localKey = $localKey;
        return $this;
    }

    public function setLocalCert($localCert): self
    {
        $this->sdkConfig->localCert = $localCert;
        return $this;
    }
    public function setRedebanCert($redebanCert): self
    {
        $this->sdkConfig->redebanCert = $redebanCert;
        return $this;
    }

    public function setLocalEncryptCert($localEncryptCert): self
    {
        $this->sdkConfig->localEncryptCert = $localEncryptCert;
        return $this;
    }

    public function setRedebanEncryptCert($redebanEncryptCert): self
    {
        $this->sdkConfig->redebanEncryptCert = $redebanEncryptCert;
        return $this;
    }

    public function setEnvironment($environment): self
    {
        $this->sdkConfig->environment = $environment;
        return $this;
    }

    public function build(): void
    {
        ApiSacConfig::getInstance()->setConfig($this->sdkConfig);
    }
}
