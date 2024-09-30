<?php

namespace Epayco\SdkRedeban\Repositories;

use Epayco\SdkRedeban\Adapters\HttpClientAdapter;
use Epayco\SdkRedeban\DTOs\Apisac\DataConfigSdkDto;
use Epayco\SdkRedeban\Helpers\Apisac\ApiSacConfig;
use Epayco\SdkRedeban\Helpers\Apisac\ApiSacParams;

class ApiSacRepository
{
    use ApiSacParams;
    private ApiSacConfig $sdkConfig;
    private DataConfigSdkDto $apiSacConfig;
    private string $sdkRealPath;
    private HttpClientAdapter $httpClientAdapter;
    private const REDEBAN_ADJUSTMENT_REGISTER_PATH = '/cxf/servicios/sac/v2/registrarAjuste';
    private const REDEBAN_ADJUSTMENT_CONSULT_PATH = '/cxf/servicios/sac/v2/consultarAjuste';

    public function __construct()
    {
        $this->sdkConfig = ApiSacConfig::getInstance();
        $this->apiSacConfig = $this->sdkConfig->getConfig();
        $this->httpClientAdapter = new HttpClientAdapter($this->sdkConfig);
        $this->sdkRealPath = realpath(__DIR__ . '/..');
    }

    public function refund($data): ?object
    {
        $apiSacParams = $this->getParams($this->apiSacConfig->environment);
        $apiSacBaseUrl = "$apiSacParams->baseUrl:$apiSacParams->port" . self::REDEBAN_ADJUSTMENT_REGISTER_PATH;
        $headers = [
            'ID_Cliente' => $apiSacParams->clientId,
        ];

        return $this->httpClientAdapter->doRequest($apiSacBaseUrl, $data, 'POST', $headers);
    }

    public function showRefund($data): ?object
    {
        $apiSacParams = $this->getParams($this->apiSacConfig->environment);
        $apiSacBaseUrl = "$apiSacParams->baseUrl:$apiSacParams->port" . self::REDEBAN_ADJUSTMENT_CONSULT_PATH;
        $headers = [
            'ID_Cliente' => $apiSacParams->clientId,
        ];

        return $this->httpClientAdapter->doRequest($apiSacBaseUrl, $data, 'POST', $headers);
    }

}
