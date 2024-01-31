# sdk_redeban_php
# Para la instalación agregar en su composer.json lo siguiente:
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/epayco/sdk_redeban_php",
            "options": {
                "ssl": {
                    "verify_peer": true,
                    "verify_peer_name": true
                }
            }
        }
    ],
    "require": {
        "epayco/sdk_redeban_php": "dev-master"
    }
}

Ejecute "composer install"

# Para probar el sdk despues de instalar:

<?php
require 'vendor/autoload.php';

use Epayco\SdkRedeban\EpaycoSdkRedeban;

$obj = new EpaycoSdkRedeban();
echo $obj->helloWorld();