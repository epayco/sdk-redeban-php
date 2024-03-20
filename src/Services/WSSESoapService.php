<?php

namespace App\Services\Redeban;

use DOMDocument;
use Exception;
use RobRichards\WsePhp\WSSESoap;
use RobRichards\XMLSecLibs\XMLSecurityKey;
use SoapClient;

class WSSESoapService extends SoapClient
{
    public ?string $username = null;
    public ?string $password = null;
    public $localCert = null;
    public $localPrivateKey = null;
    public $serviceCert = null;

    public function __doRequest($request, $location, $action, $version, $oneWay = false): ?string
    {
        try {
            $soapRequest = $this->buildSoapRequest($request);
            $soapResponse = parent::__doRequest($soapRequest, $location, $action, $version);
            $this->logSoapResponse($soapResponse, $soapRequest);

            $decryptedResponse = $this->decryptSoapResponse($soapResponse);
            $this->logDecryptedResponse($decryptedResponse);

            return $decryptedResponse;
        } catch (Exception $e) {
            $this->logError($e);
        }
        return null;
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

    private function logSoapResponse($response, $request): void
    {
        // Log::info(
        //     "api_rbm_soap_response",
        //     [
        //         "rHeaders" => $this->__getLastResponseHeaders(),
        //         "rResponse" => $response, "request" => $request
        //     ]
        // );
        //TODO log
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

    private function logDecryptedResponse($response): void
    {
        // Log::info("api_rbm_soap_response", [
        //     "decrypt" => $response
        // ]);
        //TODO log
    }

    private function logError(Exception $e): void
    {
        // Log::error('API Redeban error', [
        //     "message" => $e->getMessage(),
        //     "trace" => $e->getTraceAsString()
        // ]);
        //TODO log
    }

}
