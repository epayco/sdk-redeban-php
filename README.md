# SDK Redeban PHP

## Instalación

Para instalar la librería, sigue estos pasos:

1. Agrega el repositorio a tu `composer.json`:

    ```json
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
    ```

2. Ejecuta el siguiente comando para instalar la librería:

    ```bash
    composer install
    ```

## Uso

Para probar el SDK después de la instalación, puedes utilizar el siguiente código en tu aplicación PHP:

```php
<?php
require 'vendor/autoload.php';

use Epayco\SdkRedeban\EpaycoSdkRedeban;

$obj = new EpaycoSdkRedeban();
echo $obj->helloWorld();
