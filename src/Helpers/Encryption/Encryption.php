<?php

namespace Helpers\Encryption;

interface Encryption
{
    public function encrypt(string $data, array $options);
    public function decrypt(string $data, array $options);
}
