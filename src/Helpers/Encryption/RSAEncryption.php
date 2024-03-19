<?php
namespace Helpers\Encryption;

use Firebase\JWT\JWT;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Encryption\Algorithm\KeyEncryption\RSAOAEP256;
use Jose\Component\Encryption\Compression\Deflate;
use Jose\Component\Encryption\Compression\CompressionMethodManager;
use Jose\Component\Encryption\JWEBuilder;
use Jose\Component\Encryption\Serializer\JWESerializerManager;
use Jose\Component\Encryption\Algorithm\ContentEncryption\A256CBCHS512;
use Jose\Component\KeyManagement\JWKFactory;
use Jose\Component\Encryption\Serializer;

class RSAEncryption implements Encryption
{

    private string $keyEncryptAlg;
    private string $contentEncryptAlg;
    private const REDEBAN_PASARELA_ID_CLIENT = 28;

    public function __construct(string $keyEncryptAlg = "RSA-OAEP-256", string $contentEncryptAlg = "A256CBC-HS512")
    {
        $this->keyEncryptAlg = $keyEncryptAlg;
        $this->contentEncryptAlg = $contentEncryptAlg;
    }

    public function encrypt(string $data, array $options): string
    {
        $clientPrivateKey = env('LOCAL_KEY');
        $serverPublicKey = env('REDEBAN_CERT');

        $publicKeyServer = JWKFactory::createFromKey($serverPublicKey,
            null,
            [
                'kid' => 'My Public RSA key',
                'use' => 'enc',
                'alg' => 'RSA-OAEP-256',
                'ID_Cliente' => self::REDEBAN_PASARELA_ID_CLIENT
            ]
        );

        $jwt = new Jwt();
        $jws = $jwt->sign($clientPrivateKey, null, 'RS256');  // No need to call toString()

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
            ->create()        // We want to create a new JWE
            ->withPayload($jws) // We set the payload
            ->withSharedProtectedHeader([
                'alg' => 'RSA-OAEP-256',
                'enc' => 'A256CBC-HS512',
                'zip' => 'DEF',
            ])
            ->addRecipient($publicKeyServer)  // We add a recipient (a shared key or public key).
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


    public function decrypt(string $data, array $options): string
    {
        $decodedData = base64_decode($data);
        openssl_private_decrypt($decodedData, $decryptedData, $options['key'], OPENSSL_PKCS1_PADDING);
        return $decryptedData;
    }

}
