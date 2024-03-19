<?php
namespace Epayco\SdkRedeban\Repositories;

use Epayco\SdkRedeban\helpers\XmltoArrayHelper;

class RedebanRepository
{
    public function shopRequest($data)
    {
        return $data;
    }
    public function reverse($data)
    {
        $xmlString = $this->reverseDemoResponse();
        $data = XmltoArrayHelper::convertToArray($xmlString);
        return $data;
    }
    private function reverseDemoResponse(){
        return '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:com="http://www.rbm.com.co/esb/comercio/compra/" xmlns:esb="http://www.rbm.com.co/esb/" xmlns:com1="http://www.rbm.com.co/esb/comercio/">
        <soapenv:Body>
           <com:compraReversarRespuesta>
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
                 <com:fechaTransaccion>2019-08-15T21:22:14</com:fechaTransaccion>
                 <com:fechaPosteo>2019-08-15</com:fechaPosteo>
                 <com:numAprobacion>025490</com:numAprobacion>
              </com:infoCompraResp>
              <com:idTransaccionAutorizador>000000999998</com:idTransaccionAutorizador>
           </com:compraReversarRespuesta>
        </soapenv:Body>
     </soapenv:Envelope>';
    }

}