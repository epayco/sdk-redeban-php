<?php

namespace Epayco\SdkRedeban\Helpers\Apisac;
trait ApiSacParams {
    public function getParams(string $environment = 'test'): object
    {
        return (object) match ($environment) {
            'production', 'green' => [
                'baseUrl' => 'https://www.txsprodrbm.com',
                'port' => 8450,
                'clientId' => 28,
            ],
            default => [
                'baseUrl' => 'https://www.txstestrbm.com',
                'port' => 8460,
                'clientId' => 28,
            ],
        };
    }

}
