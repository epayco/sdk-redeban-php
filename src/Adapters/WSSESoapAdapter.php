<?php

namespace Epayco\SdkRedeban\Adapters;

use DOMDocument;
use Exception;
use RobRichards\WsePhp\WSSESoap;
use RobRichards\XMLSecLibs\XMLSecurityKey;
use SoapClient;

class WSSESoapAdapter extends SoapClient
{
    public ?string $username = null;
    public ?string $password = null;
    public mixed $localCert = null;
    public mixed $localPrivateKey = null;
    public mixed $serviceCert = null;

    public function __doRequest($request, $location, $action, $version, $oneWay = false): ?string
    {
        try {
            $soapRequest = $this->buildSoapRequest($request);
            $soapResponse = parent::__doRequest($soapRequest, $location, $action, $version);

            return $this->decryptSoapResponse($soapResponse);

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @throws Exception
     */
    private function buildSoapRequest($request)
    {
        $doc = new DOMDocument('1.0');
        $doc->loadXML($request);

        $wsseSoap = new WSSESoap($doc);
        $wsseSoap->addTimestamp(300);

        $privateKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, array('type' => 'private'));
        $privateKey->loadKey($this->localPrivateKey, false);

        $options = array("insertBefore" => false);

        $wsseSoap->addUserToken($this->username, $this->password);
        $wsseSoap->signAllHeaders = true;
        $wsseSoap->signSoapDoc($privateKey, $options);

        $token = $wsseSoap->addBinaryToken($this->localCert);
        $wsseSoap->attachTokentoSig($token);

        $sessionKey = new XMLSecurityKey(XMLSecurityKey::AES256_CBC);
        $sessionKey->generateSessionKey();

        $siteKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA512, array('type' => 'public'));
        $siteKey->loadKey($this->serviceCert, false, false);

        return $wsseSoap->saveXML();
    }

    /**
     * @throws Exception
     */
    private function decryptSoapResponse($response): false|string
    {
        $doc = new DOMDocument();
        $doc->loadXML($response);

        $options = [
            "keys" => [
                "private" => [
                    "key" => $this->localPrivateKey,
                    "isFile" => false,
                    "isCert" => false
                ]
            ]
        ];
        $wsseSoap = new WSSESoap($doc);
        $wsseSoap->decryptSoapDoc($doc, $options);

        return $doc->saveXML();
    }

}
