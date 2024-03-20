<?php

namespace Helpers\Encryption;

class EncryptionManager
{
    private Encryption $strategy;

    public function __construct(Encryption $strategy)
    {
        $this->strategy = $strategy;
    }

    public function encryptData(string $data, array $options)
    {
        return $this->strategy->encrypt($data, $options);
    }

    public function decryptData(string $data, array $options)
    {
        return $this->strategy->decrypt($data, $options);
    }

}
