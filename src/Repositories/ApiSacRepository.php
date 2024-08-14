<?php

namespace Epayco\SdkRedeban\Repositories;

use Epayco\SdkRedeban\Helpers\Apisac\ApiSacConfig;
use Epayco\SdkRedeban\Helpers\Apisac\ApiSacParams;
use SoapFault;
use stdClass;

class ApiSacRepository
{
    use ApiSacParams;
    private ApiSacConfig $sdkConfig;
    private string $sdkRealPath;
    private const REDEBAN_ADJUSTMENT_REGISTER_PATH = '/registrarAjuste';

    public function __construct()
    {
        $this->sdkConfig = ApiSacConfig::getInstance();
        $this->sdkRealPath = realpath(__DIR__ . '/..');
    }

    /**
     * @throws SoapFault
     */
    public function refund($data): ?stdClass
    {
        $response = new stdClass();
        $apiSacConfig = $this->sdkConfig->getConfig();
        $apiSacParams = $this->getParams($apiSacConfig->environment);
        $apiSacBaseUrl = "$apiSacParams->baseUrl:$apiSacParams->port";

        $paycoClient = $this->getSoapSecutiryClient($wsdlPath);

        $response->providerResponse = $paycoClient->compraCancelacionProcesar($data);
        $response->logs = $this->getXmlRequestResponse($paycoClient, $maskCard);

        return $response;
    }

    /**
     * @throws SoapFault
     */
    public function getSoapSecutiryClient($wsdlPath): WSSESoapAdapter
    {
        $ePurchaseConfig = $this->sdkConfig->getConfig();
        $environment = $ePurchaseConfig->environment ?? 'test';

        $urlWebService = realpath("$this->sdkRealPath/Utils/$environment/electronic/$wsdlPath");

        $localPrivateKey = $ePurchaseConfig->localPrivateKey;
        $localCert = $ePurchaseConfig->localCert;
        $serviceCert = $ePurchaseConfig->redebanCert;

        $certs = $this->generateCertFiles();

        $optionsSecurity = [
            'cache_wsdl' => WSDL_CACHE_NONE,
            'trace' => 1,
            'stream_context' => stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => false,
                    'crypto_method' => STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT,
                    'local_cert' => $certs['local_cert'],
                    'local_pk' => $certs['local_pk'],
                    'cafile' => $certs['cafile'],
                ],
            ]),
        ];

        $paycoClient = new WSSESoapAdapter($urlWebService, $optionsSecurity);

        $paycoClient->username = $ePurchaseConfig->username;
        $paycoClient->password = $ePurchaseConfig->password;
        $paycoClient->localPrivateKey = $localPrivateKey;
        $paycoClient->localCert = $localCert;
        $paycoClient->serviceCert = $serviceCert;

        return $paycoClient;
    }

}
