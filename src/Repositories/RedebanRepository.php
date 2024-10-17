<?php

namespace Epayco\SdkRedeban\Repositories;

use Epayco\SdkRedeban\Adapters\WSSESoapAdapter;
use Epayco\SdkRedeban\Helpers\SDKConfig;

use SoapFault;

class RedebanRepository
{
    private SDKConfig $sdkConfig;
    private string $sdkRealPath;

    public function __construct()
    {
        $this->sdkConfig = SDKConfig::getInstance();
        $this->sdkRealPath = realpath(__DIR__ . '/..');
    }

    /**
     * @throws SoapFault
     */
    public function purchase($data)
    {
        $wsdlPath = 'process/CompraElectronicaService_2.wsdl';

        $paycoClient = $this->getSoapSecutiryClient($wsdlPath);

        return $paycoClient->compraProcesar($data);
    }

    /**
     * @throws SoapFault
     */
    public function reverse($data)
    {
        $wsdlPath = 'process/CompraElectronicaService_2.wsdl';

        $paycoClient = $this->getSoapSecutiryClient($wsdlPath);

        return $paycoClient->compraReversar($data);
    }

    /**
     * @throws SoapFault
     */
    public function cancel($data)
    {
        $wsdlPath = 'cancel/CompraElectronicaCancelacionService.wsdl';

        $paycoClient = $this->getSoapSecutiryClient($wsdlPath);

        return $paycoClient->compraCancelacionProcesar($data);
    }

    /**
     * @throws SoapFault
     */
    public function getSoapSecutiryClient($wsdlPath): WSSESoapAdapter
    {
        $environment = $this->sdkConfig->getConfig('environment') ?? 'test';

        $urlWebService = realpath("$this->sdkRealPath/Utils/$environment/present/$wsdlPath");

        $localPrivateKey = $this->sdkConfig->getConfig('localPrivateKey');
        $localCert = $this->sdkConfig->getConfig('localCert');
        $serviceCert = $this->sdkConfig->getConfig('redebanCert');

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
                    'passphrase' => '123456',
                ],
            ]),
        ];

        $paycoClient = new WSSESoapAdapter($urlWebService, $optionsSecurity);

        $paycoClient->username = $this->sdkConfig->getConfig('username');
        $paycoClient->password = $this->sdkConfig->getConfig('password');
        $paycoClient->localPrivateKey = $localPrivateKey;
        $paycoClient->localCert = $localCert;
        $paycoClient->serviceCert = $serviceCert;

        return $paycoClient;
    }

    public function generateCertFiles(): array
    {
        $certs = [];
        $localPrivateKeyPath = $this->sdkRealPath . "/Utils/cert/present/local_key.pem";
        $localCertPath = $this->sdkRealPath . "/Utils/cert/present/local_cert.pem";
        $serviceCertPath = $this->sdkRealPath . "/Utils/cert/present/rbm_cert.pem";
        if (!file_exists($localPrivateKeyPath)) {
            file_put_contents(
                $localPrivateKeyPath,
                $this->sdkConfig->getConfig('localPrivateKey')
            );
        }
        if (!file_exists($localCertPath)) {
            file_put_contents(
                $localCertPath,
                $this->sdkConfig->getConfig('localCert')
            );
        }
        if (!file_exists($serviceCertPath)) {
            file_put_contents(
                $serviceCertPath,
                $this->sdkConfig->getConfig('serviceCert')
            );
        }
        $certs['local_pk'] = realpath($localPrivateKeyPath);
        $certs['local_cert'] = realpath($localCertPath);
        $certs['cafile'] = realpath($serviceCertPath);

        return $certs;
    }

}
