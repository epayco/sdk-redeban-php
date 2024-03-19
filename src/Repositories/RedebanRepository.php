<?php
namespace Epayco\SdkRedeban\repositories;

use Helpers\Encryption\EncryptionManager;
use Helpers\Encryption\RSAEncryption;
use Carbon\Carbon;
use Gaarf\XmlToPhp\Convertor;

class RedebanRepository
{
    public function shopRequest($data)
    {
        $xmlString = $this->demoResponse();
        $data = Convertor::covertToArray($xmlString);
        return $data;
    }

    public function shop()
    {

        $data = $this->reverseTest();
        $rsaEncryption = new RSAEncryption();
        $encryptionManager = new EncryptionManager($rsaEncryption);
        $encryptedData = $encryptionManager->encryptData($data, []);
        // var_dump($encryptedData); die();
        return $encryptedData;

    }

    private function demoResponse()
    {
        return '<soapenv:Envelope xmlns:com1="http://www.rbm.com.co/esb/comercio/" xmlns:esb="http://www.rbm.com.co/esb/" xmlns:com="http://www.rbm.com.co/esb/comercio/compra/" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
            <soapenv:Body>
                <com:compraProcesarRespuesta>
                    <com:cabeceraRespuesta>
                        <com:infoPuntoInteraccion>
                            <com1:tipoTerminal>WEB</com1:tipoTerminal>
                            <com1:idTerminal>ESB10001</com1:idTerminal>
                            <com1:idAdquiriente>0010203040</com1:idAdquiriente>
                            <com1:idTransaccionTerminal>999998</com1:idTransaccionTerminal>
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
                        <com:fechaTransaccion>2019-08-01T10:25:48</com:fechaTransaccion>
                        <com:fechaPosteo>2019-08-01</com:fechaPosteo>
                        <com:numAprobacion>025490</com:numAprobacion>
                        <com:infoAdicional>
                            <esb:tipoInfo>datosToken</esb:tipoInfo>
                            <esb:descripcion>XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX</esb:descripcion>
                        </com:infoAdicional>
                    </com:infoCompraResp>
                    <com:idTransaccionAutorizador>000000999998</com:idTransaccionAutorizador>
                    <com:infoTerminal>
                        <com1:nombreAdquiriente>NOMBRE ALIADOPOS</com1:nombreAdquiriente>
                        <com1:infoUbicacion>
                            <esb:ciudad>1100100 BOGOT</esb:ciudad>
                            <esb:departamento>BOG</esb:departamento>
                            <esb:pais>CO</esb:pais>
                        </com1:infoUbicacion>
                    </com:infoTerminal>
                </com:compraProcesarRespuesta>
            </soapenv:Body>
        </soapenv:Envelope>';
    }

    public function reverseTest(): ?string
    {
        $formattedDate = Carbon::createFromFormat('Y-m-d H:i:s', '2023-06-01 00:00:00')->format('Y-m-d');

        $reverseRequest = new \stdClass();
        $reverseRequest->codigoUnico = '11174646';
        $reverseRequest->fechaTransaccion = $formattedDate;
        $reverseRequest->tarjeta = '9388';
        $reverseRequest->valorOriginal = 16755;
        $reverseRequest->numeroAprobacion = '180107';
        $reverseRequest->tipoAjuste = 'T';
        $reverseRequest->IDPasarela = 28;

        return json_encode($reverseRequest);
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

        // $rsaEncryption = new RSAEncryption();
        // $encryptionManager = new EncryptionManager($rsaEncryption);
        // $encryptedData = $encryptionManager->encryptData($objRequest, []);
        
        $xmlString = $this->voidResponse();
        $data = Convertor::covertToArray($xmlString);
        return $data;
    }
}