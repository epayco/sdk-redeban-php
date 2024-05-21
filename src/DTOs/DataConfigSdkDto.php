<?php
namespace Epayco\SdkRedeban\DTOs;

class DataConfigSdkDto
{
    public string $username;
    public string $password;
    public mixed $localCert;
    public mixed $localPrivateKey;
    public mixed $redebanCert;
    public string $environment;
}
