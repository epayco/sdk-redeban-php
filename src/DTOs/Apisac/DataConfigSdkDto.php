<?php
namespace Epayco\SdkRedeban\DTOs\Apisac;

class DataConfigSdkDto
{
    public string $username;
    public string $password;
    public mixed $localKey;
    public mixed $localCert;
    public mixed $redebanCert;
    public mixed $localEncryptCert;
    public mixed $redebanEncryptCert;
    public string $environment;
}
