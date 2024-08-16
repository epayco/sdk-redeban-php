<?php

namespace Epayco\SdkRedeban\Repositories;

use Epayco\SdkRedeban\Adapters\HttpClientAdapter;
use Epayco\SdkRedeban\Helpers\Apisac\ApiSacConfig;
use Epayco\SdkRedeban\Helpers\Apisac\ApiSacParams;

class ApiSacRepository
{
    use ApiSacParams;
    private ApiSacConfig $sdkConfig;
    private string $sdkRealPath;
    private HttpClientAdapter $httpClientAdapter;
    private const REDEBAN_ADJUSTMENT_REGISTER_PATH = '/cxf/servicios/sac/v2/registrarAjuste';

    public function __construct()
    {
        $this->sdkConfig = ApiSacConfig::getInstance();
        $this->httpClientAdapter = new HttpClientAdapter($this->sdkConfig);
        $this->sdkRealPath = realpath(__DIR__ . '/..');
    }

    public function refund($data): ?object
    {
        $apiSacConfig = $this->sdkConfig->getConfig();
        $apiSacParams = $this->getParams($apiSacConfig->environment);
        $apiSacBaseUrl = "$apiSacParams->baseUrl:$apiSacParams->port" . self::REDEBAN_ADJUSTMENT_REGISTER_PATH;
        $headers = [
            'ID_Cliente' => $apiSacParams->clientId,
        ];

        return $this->httpClientAdapter->doRequest($apiSacBaseUrl, $data, 'POST', $headers);
    }

}
