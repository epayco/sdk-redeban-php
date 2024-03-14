<?php

namespace Epayco\SdkRedeban\validations;

use Epayco\SdkRedeban\validations\Validation;
use Respect\Validation\Validator as v;

class ShopValidation extends Validation
{

    public function __invoke($request)
    {
        $requestValidator = [
            'reference' => v::notEmpty()->length(6, 10),
            'amount' => v::notEmpty()->numericVal()->positive(),
        ];

        $errorMessages = [
            'terminalType' => 'El campo terminalType es un string requerido',
            'amountTax' => 'El campo amountTax debe ser un número positivo.',
        ];

        return $this->validate($requestValidator, $errorMessages, $request);
    }
    
}
