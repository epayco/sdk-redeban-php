<?php
namespace Epayco\SdkRedeban\Helpers\Apisac;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Encryption\Algorithm\ContentEncryption\A256CBCHS512;
use Jose\Component\Encryption\Algorithm\KeyEncryption\RSAOAEP256;
use Jose\Component\Encryption\JWEBuilder;
use Jose\Component\Encryption\Serializer;
use Jose\Component\Encryption\Serializer\JWESerializerManager;
use Jose\Component\KeyManagement\JWKFactory;
use stdClass;

class RSAEncryption implements Encryption
{
    private ApiSacConfig $sdkConfig;
    private string $keyEncryptAlg;
    private string $contentEncryptAlg;
    public function __construct(string $keyEncryptAlg = "RSA-OAEP-256", string $contentEncryptAlg = "A256CBC-HS512")
    {
        $this->keyEncryptAlg = $keyEncryptAlg;
        $this->contentEncryptAlg = $contentEncryptAlg;
        $this->sdkConfig = ApiSacConfig::getInstance();
    }

    public function encrypt(mixed $data, ?array $options): ?string
    {
        $apiSacConfig = $this->sdkConfig->getConfig();
        $clientPrivateKey = $apiSacConfig->localEncryptCert;
        $serverPublicKey = $apiSacConfig->redebanEncryptCert;

        $publicKeyServer = JWKFactory::createFromKey(
            $serverPublicKey,
            null,
            [
                'kid' => 'My Public RSA key',
                'use' => 'enc',
                'alg' => $this->keyEncryptAlg,
                'ID_Cliente' => $options['redebanClientId'] ?? 28
            ]
        );

        $dataArray = json_decode(json_encode($data), true);
        $jwt = JWT::encode($dataArray, $clientPrivateKey, 'RS256');

        $jweBuilder = new JWEBuilder(
            new AlgorithmManager([new RSAOAEP256()]),
            new AlgorithmManager([new A256CBCHS512()])
        );

        $jwe = $jweBuilder
            ->create()
            ->withPayload($jwt) // Set the payload
            ->withSharedProtectedHeader([
                'alg' => $this->keyEncryptAlg,
                'enc' => $this->contentEncryptAlg,
            ])
            ->addRecipient($publicKeyServer)
            ->build();
        $manager = new JWESerializerManager([
            new Serializer\JSONFlattenedSerializer()
        ]);

        return $manager->serialize('jwe_json_flattened', $jwe);
    }

    public function decrypt(mixed $data, ?array $options): ?stdClass
    {
        $apiSacConfig = $this->sdkConfig->getConfig();
        $redebanPublicKey = $apiSacConfig->redebanEncryptCert;
        $key = new Key($redebanPublicKey, 'RS256');
        $headers = new stdClass();
        return JWT::decode($data, $key, $headers);
    }

}
