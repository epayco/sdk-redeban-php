<?php
namespace Epayco\SdkRedeban\DTOs\Electronic;

class DataConfigSdkDto
{
    public string $username;
    public string $password;
    public mixed $localCert;
    public mixed $localPrivateKey;
    public mixed $redebanCert;
    public string $environment;
}
