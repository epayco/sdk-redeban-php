<?php

namespace Epayco\SdkRedeban\Helpers\Apisac;

interface Encryption
{
    public function encrypt(mixed $data, ?array $options);
    public function decrypt(mixed $data, ?array $options);
}
