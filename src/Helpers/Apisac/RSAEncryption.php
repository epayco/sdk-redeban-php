<?php
namespace Epayco\SdkRedeban\Helpers\Apisac;

use Firebase\JWT\JWT;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Encryption\Algorithm\ContentEncryption\A256CBCHS512;
use Jose\Component\Encryption\Algorithm\KeyEncryption\RSAOAEP256;
use Jose\Component\Encryption\Compression\CompressionMethodManager;
use Jose\Component\Encryption\Compression\Deflate;
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

    public function encrypt(string $data, ?array $options): ?string
    {
        $apiSacConfig = $this->sdkConfig->getConfig();
        $clientPrivateKey = $apiSacConfig->encryptCert;
        $serverPublicKey = $apiSacConfig->redebanEncryptCert;

        $publicKeyServer = JWKFactory::createFromKey(
            $serverPublicKey,
            null,
            [
                'kid' => 'My Public RSA key',
                'use' => 'enc',
                'alg' => $this->keyEncryptAlg,
                'ID_Cliente' => $apiSacConfig->redebanClientId
            ]
        );

        $jwt = new Jwt();
        $jws = $jwt->sign($clientPrivateKey, null, 'RS256');

        $keyEncryptionAlgorithmManager = new AlgorithmManager([
            new RSAOAEP256(),
        ]);

        $contentEncryptionAlgorithmManager = new AlgorithmManager([
            new A256CBCHS512(),
        ]);

        $compressionMethodManager = new CompressionMethodManager([
            new Deflate(),
        ]);

        $jweBuilder = new JWEBuilder(
            $keyEncryptionAlgorithmManager,
            $contentEncryptionAlgorithmManager,
            $compressionMethodManager
        );

        $jwe = $jweBuilder
            ->create()          // Create a new JWE
            ->withPayload($jws) // Set the payload
            ->withSharedProtectedHeader([
                'alg' => $this->keyEncryptAlg,
                'enc' => $this->contentEncryptAlg,
                'zip' => 'DEF',
            ])
            ->addRecipient($publicKeyServer)  // Add a recipient (a shared key or public key).
            ->build();
        $manager = new JWESerializerManager([
            new Serializer\JSONFlattenedSerializer()
        ]);

        return $manager->serialize('jwe_json_flattened', $jwe);
    }

//    public function encrypt($data, array $options)
//    {
//        $clientPrivateKey = env('LOCAL_KEY');
//        $serverPublicKey = env('REDEBAN_CERT');
//
//        $publicKeyServer = JWKFactory::createFromKey($serverPublicKey,
//            null,
//            [
//                'kid' => 'My Public RSA key',
//                'use' => 'enc',
//                'alg' => 'RSA-OAEP-256',
//                'ID_Cliente' => self::REDEBAN_PASARELA_ID_CLIENT
//            ]
//        );
//
//        $jwt = new JOSE_JWT($data);
//        $jws = $jwt->sign($clientPrivateKey, 'RS256')->toString();
//
//        $keyEncryptionAlgorithmManager = new AlgorithmManager([
//            new RSAOAEP256(),
//        ]);
//
//        $contentEncryptionAlgorithmManager = new AlgorithmManager([
//            new A256CBCHS512(),
//        ]);
//
//        $compressionMethodManager = new CompressionMethodManager([
//            new Deflate(),
//        ]);
//
//        $jweBuilder = new JWEBuilder(
//            $keyEncryptionAlgorithmManager,
//            $contentEncryptionAlgorithmManager,
//            $compressionMethodManager
//        );
//
//        $jwe = $jweBuilder
//            ->create()              // We want to create a new JWE
//            ->withPayload($jws) // We set the payload
//            ->withSharedProtectedHeader( [
//                'alg' => 'RSA-OAEP-256',
//                'enc' => 'A256CBC-HS512',
//                'zip' => 'DEF',
//            ])
//            ->addRecipient($publicKeyServer)    // We add a recipient (a shared key or public key).
//            ->build();
//
//        $manager = new JWESerializerManager([
//            new Serializer\JSONFlattenedSerializer()
//        ]);
//
//        return $manager->serialize('jwe_json_flattened', $jwe);
//
//    }


    public function decrypt(string $data, ?array $options): ?stdClass
    {
        $apiSacConfig = $this->sdkConfig->getConfig();
        $redebanPublicKey = $apiSacConfig->redebanEncryptCert;
        $headers = new stdClass();
        return JWT::decode($data, $redebanPublicKey, $headers);
    }

}
