<?php

namespace Epayco\SdkRedeban\Repositories;

use Epayco\SdkRedeban\Adapters\WSSESoapAdapter;
use Epayco\SdkRedeban\Helpers\PurchaseConfig;

use SoapFault;
use stdClass;

class PurchaseElectronicRepository
{
    private PurchaseConfig $sdkConfig;
    private string $sdkRealPath;

    public function __construct()
    {
        $this->sdkConfig = PurchaseConfig::getInstance();
        $this->sdkRealPath = realpath(__DIR__ . '/..');
    }

    /**
     * @throws SoapFault
     */
    public function purchase($data, $maskCard): ?stdClass
    {
        $response = new stdClass();
        $wsdlPath = 'process/CompraElectronicaService.wsdl';
        $paycoClient = $this->getSoapSecutiryClient($wsdlPath);

        $response->providerResponse = $paycoClient->compraProcesar($data);
        $response->logs = $this->getXmlRequestResponse($paycoClient, $maskCard);

        return $response;
    }

    /**
     * @throws SoapFault
     */
    public function show($data): ?stdClass
    {
        $response = new stdClass();
        $wsdlPath = 'consult/ConsultaEstadoDePagoService.wsdl';

        $paycoClient = $this->getSoapSecutiryClient($wsdlPath);

        $response->providerResponse = $paycoClient->ConsultaEstadoDePago($data);
        $response->logs = $this->getXmlRequestResponse($paycoClient);

        return $response;
    }

    /**
     * @throws SoapFault
     */
    public function reverse($data, $maskCard): ?stdClass
    {
        $response = new stdClass();
        $wsdlPath = 'process/CompraElectronicaService.wsdl';

        $paycoClient = $this->getSoapSecutiryClient($wsdlPath);

        $response->providerResponse = $paycoClient->compraReversar($data);
        $response->logs = $this->getXmlRequestResponse($paycoClient, $maskCard);

        return $response;
    }

    /**
     * @throws SoapFault
     */
    public function refund($data, $maskCard): ?stdClass
    {
        $response = new stdClass();
        $wsdlPath = 'cancel/CompraElectronicaCancelacionService.wsdl';

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

    protected function generateCertFiles(): array
    {
        $ePurchaseConfig = $this->sdkConfig->getConfig();
        $certs = [];
        $localPrivateKeyPath = $this->sdkRealPath . "/Utils/cert/electronic/local_key.pem";
        $localCertPath = $this->sdkRealPath . "/Utils/cert/electronic/local_cert.pem";
        $serviceCertPath = $this->sdkRealPath . "/Utils/cert/electronic/rbm_cert.pem";
        if (!file_exists($localPrivateKeyPath)) {
            file_put_contents(
                $localPrivateKeyPath,
                $ePurchaseConfig->localPrivateKey
            );
        }
        if (!file_exists($localCertPath)) {
            file_put_contents(
                $localCertPath,
                $ePurchaseConfig->localCert
            );
        }
        if (!file_exists($serviceCertPath)) {
            file_put_contents(
                $serviceCertPath,
                $ePurchaseConfig->redebanCert
            );
        }
        $certs['local_pk'] = realpath($localPrivateKeyPath);
        $certs['local_cert'] = realpath($localCertPath);
        $certs['cafile'] = realpath($serviceCertPath);

        return $certs;
    }

    protected function getXmlRequestResponse(WSSESoapAdapter $paycoClient, ?string $maskedCard = null): stdClass
    {
        $xml = new stdClass();
        $xmlRequest = $paycoClient->__getLastRequest() ?? '';
        $xml->requestXml = $maskedCard ? $this->cleanXmlRequest($maskedCard, $xmlRequest) : $xmlRequest;
        $xml->responseXml = $paycoClient->__getLastResponse() ?? '';

        return $xml;
    }

    private function cleanXmlRequest(string $maskedCard, string $xmlRequest): ?string
    {
        if (!$xmlRequest) {
            return null;
        }

        $xmlRequest = $this->replaceTagContent('numTarjeta', $maskedCard, $xmlRequest);
        $xmlRequest = $this->replaceTagContent('codVerificacion', '', $xmlRequest);
        return $this->replaceTagContent('fechaExpiracion', '', $xmlRequest);
    }

    private function replaceTagContent(string $tagName, string $newContent, string $xml): string
    {
        $replacement = "<ns3:$tagName>$newContent</ns3:$tagName>";
        return preg_replace(
            "#(<ns3:$tagName.*?>).*?(</ns3:$tagName>)#",
            $replacement,
            $xml
        );
    }

}
