<?php

namespace Epayco\SdkRedeban\Helpers\Apisac;

class EncryptionManager
{
    private Encryption $strategy;

    public function __construct(Encryption $strategy)
    {
        $this->strategy = $strategy;
    }

    public function encryptData(mixed $data, ?array $options)
    {
        return $this->strategy->encrypt($data, $options);
    }

    public function decryptData(mixed $data, ?array $options)
    {
        return $this->strategy->decrypt($data, $options);
    }

}
