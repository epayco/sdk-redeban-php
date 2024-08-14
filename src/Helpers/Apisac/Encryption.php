<?php

namespace Epayco\SdkRedeban\Helpers\Apisac;

interface Encryption
{
    public function encrypt(string $data, ?array $options);
    public function decrypt(string $data, ?array $options);
}
