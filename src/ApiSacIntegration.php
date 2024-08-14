<?php

namespace Epayco\SdkRedeban;

use Epayco\SdkRedeban\DTOs\Apisac\DataConfigSdkDto;
use Epayco\SdkRedeban\Helpers\Apisac\ApiSacConfig;
use Epayco\SdkRedeban\Helpers\JsonResponse;
use Epayco\SdkRedeban\Interfaces\Refund;

class ApiSacIntegration implements Refund
{
    use JsonResponse;
    private DataConfigSdkDto $sdkConfig;

    public function __construct()
    {
        $this->sdkConfig = new DataConfigSdkDto();
    }

    public function refundTransaction(
        RefundDto $request,
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
