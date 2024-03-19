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
            'reference' => 'El campo de referencia debe tener entre 6 y 10 caracteres.',
            'amount' => 'El campo de cantidad debe ser un número positivo.',
        ];

        return $this->validate($requestValidator, $errorMessages, $request);
    }
    
}
