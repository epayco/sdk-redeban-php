<?php

namespace Epayco\SdkRedeban\Adapters;

use Epayco\SdkRedeban\Helpers\Apisac\ApiSacConfig;
use Error;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class HttpClientAdapter
{
    private ApiSacConfig $sdkConfig;
    private string $sdkRealPath;

    /**
     * @param ApiSacConfig $sdkConfig
     */
    public function __construct(ApiSacConfig $sdkConfig)
    {
        $this->sdkConfig = $sdkConfig;
        $this->sdkRealPath = realpath(__DIR__ . '/..');
    }

    public function doRequest(string $url, mixed $body, string $method = 'POST', array $customHeaders = null): ?object
    {
        try {
            $headers = $this->getHeaders();
            if ($customHeaders !== null) {
                $headers = array_merge($headers, $customHeaders);
            }
            $client = new Client();

            $certs = $this->generateCertFiles('apisac');
            $options = [
                'headers' => $headers,
                'auth' => $this->getAuthCredentials(),
                'cert' => $certs['cert'],
                'ssl_key' => $certs['ssl_key'],
                'verify' => $certs['verify'],
                'body' => $body,
            ];
            $response = $client->request(
                $method,
                $url,
                $options
            );

            if ($response->getStatusCode() === 200) {
                return (object)[
                    'success' => true,
                    'code' => $response->getStatusCode(),
                    'data' => $response->getBody()->getContents(),
                ];
            }

            throw new Error($response->getStatusCode() . ' - ' . $response->getReasonPhrase());

        } catch (GuzzleException | RequestException $e) {
            return (object)[
                'success' => false,
                'code' => $e->getCode(),
                'data' => $e->getMessage()
            ];
        }
    }

    protected function generateCertFiles(string $integration): array
    {
        $apiSacConfig = $this->sdkConfig->getConfig();
        $certs = [];
        $localKeyPath = $this->sdkRealPath . "/Utils/cert/$integration/local_key.pem";
        $localCertPath = $this->sdkRealPath . "/Utils/cert/$integration/local_cert.pem";
        $serviceCertPath = $this->sdkRealPath . "/Utils/cert/$integration/rbm_cert.pem";
        if (!file_exists($localKeyPath)) {
            file_put_contents(
                $localKeyPath,
                $apiSacConfig->localKey
            );
        }
        if (!file_exists($localCertPath)) {
            file_put_contents(
                $localCertPath,
                $apiSacConfig->localCert
            );
        }
        if (!file_exists($serviceCertPath)) {
            file_put_contents(
                $serviceCertPath,
                $apiSacConfig->redebanCert
            );
        }
        $certs['ssl_key'] = realpath($localKeyPath);
        $certs['cert'] = realpath($localCertPath);
        $certs['verify'] = realpath($serviceCertPath);

        return $certs;
    }

    protected function getHeaders(): array
    {
        return [
            'cache-control' => 'no-cache',
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ];
    }

    protected function getAuthCredentials(): array
    {
        $apiSacConfig = $this->sdkConfig->getConfig();
        return [
            $apiSacConfig->username,
            $apiSacConfig->password,
        ];
    }

}
