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
            'terminalType' => 'El campo terminalType es requerido y debe ser un string.',
            'terminalId' => 'El campo terminalId es requerido y debe ser un string.',
            'acquirerId' => 'El campo acquirerId es requerido y debe ser un string.',
            'terminalTransactionId' => 'El campo terminalTransactionId es requerido y debe ser un string.',
            'panCaptureMode' => 'El campo panCaptureMode es requerido y debe ser un string.',
            'pinCapability' => 'El campo pinCapability es requerido y debe ser un string.',
            'brand' => 'El campo brand es requerido y debe ser un string.',
            'trackData' => 'El campo trackData es requerido y debe ser un string.',
            'tokenData' => 'El campo tokenData es requerido y debe ser un string.',
            'tokenStatus' => 'El campo tokenStatus es requerido y debe ser un string.',
            'discreetData' => 'El campo discreetData es requerido y debe ser un string.',
            'totalAmount' => 'El campo totalAmount es requerido y debe ser un número positivo.',
            'amountTax' => 'El campo amountTax es requerido y debe ser un número positivo.',
            'detailedAmountType' => 'El campo detailedAmountType es requerido y debe ser un string.',
            'detailedAmount' => 'El campo detailedAmount es requerido y debe ser un número positivo.',
            'reference' => 'El campo reference es requerido y debe ser un string.',
            'installmentCount' => 'El campo installmentCount es requerido y debe ser un número entero positivo.',
            'additionalDataType' => 'El campo additionalDataType es requerido.'
        ];

        return $this->validate($requestValidator, $errorMessages, $request);
    }
    
}
