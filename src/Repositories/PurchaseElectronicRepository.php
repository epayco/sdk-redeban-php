<?php

namespace Epayco\SdkRedeban\Repositories;

use Epayco\SdkRedeban\Adapters\WSSESoapAdapter;
use Epayco\SdkRedeban\Helpers\PurchaseConfig;
use Gaarf\XmlToPhp\Convertor;

use SoapFault;

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
    public function purchase($data)
    {
        $wsdlPath = 'process/CompraElectronicaService.wsdl';

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

    private function voidResponse()
    {
        return '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:com="http://www.rbm.com.co/esb/comercio/compra/" xmlns:esb="http://www.rbm.com.co/esb/" xmlns:com1="http://www.rbm.com.co/esb/comercio/">
            <soapenv:Body>
               <com:compraCancelacionProcesarRespuesta>
                  <com:cabeceraRespuesta>
                     <com:infoPuntoInteraccion>
                        <com1:tipoTerminal>WEB</com1:tipoTerminal>
                        <com1:idTerminal>ESB10001</com1:idTerminal>
                        <com1:idAdquiriente>0010203040</com1:idAdquiriente>
                        <com1:idTransaccionTerminal>999999</com1:idTransaccionTerminal>
                        <com1:modoCapturaPAN>CHIP</com1:modoCapturaPAN>
                        <com1:capacidadPIN>Permitido</com1:capacidadPIN>
                     </com:infoPuntoInteraccion>
                  </com:cabeceraRespuesta>
                  <com:infoRespuesta>
                     <esb:codRespuesta>00</esb:codRespuesta>
                     <esb:descRespuesta>Aprobado</esb:descRespuesta>
                     <esb:estado>Aprobado</esb:estado>
                  </com:infoRespuesta>
                  <com:infoCompraResp>
                     <com:fechaTransaccion>2019-08-15T21:27:04</com:fechaTransaccion>
                     <com:fechaPosteo>2019-08-15</com:fechaPosteo>
                     <com:numAprobacion>212825</com:numAprobacion>
                  </com:infoCompraResp>
                  <com:idTransaccionAutorizador>000000999998</com:idTransaccionAutorizador>
                  <com:infoTerminal>
                     <com1:nombreAdquiriente>NOMBRE ALIADOPOS</com1:nombreAdquiriente>
                     <com1:infoUbicacion>
                        <esb:ciudad>1100100BOGOTA</esb:ciudad>
                        <esb:departamento>CUN</esb:departamento>
                        <esb:pais>CO</esb:pais>
                     </com1:infoUbicacion>
                  </com:infoTerminal>
               </com:compraCancelacionProcesarRespuesta>
            </soapenv:Body>
         </soapenv:Envelope>';
    }

    function void($objRequest)
    {
        //TODO ejecución del request anular

        $xmlString = $this->voidResponse();
        $data = Convertor::covertToArray($xmlString);
        return $data;
    }

}
