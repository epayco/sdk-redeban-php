<?php

namespace Epayco\SdkRedeban\Validations;

use Epayco\SdkRedeban\Validations\Validation;
use Respect\Validation\Validator as v;

class ShopValidation extends Validation
{

    public function __invoke($request)
    {
        $requestValidator = [
            'terminalType' => v::notEmpty()->length(2, 100),
            'terminalId' => v::notEmpty()->length(2, 100),
            'acquirerId' => v::notEmpty()->length(2, 100),
            'terminalTransactionId' => v::notEmpty()->numericVal()->positive(),
            'panCaptureMode' => v::notEmpty()->length(2, 100),
            'pinCapability' => v::notEmpty()->length(2, 100),
            'brand' => v::notEmpty()->length(2, 100),
            'trackData' => v::notEmpty()->length(2, 100),
            'accountType' => v::stringVal()->notEmpty()->length(2, 100),
            'tokenData' => v::notEmpty()->length(2, 200),
            'tokenStatus' => v::notEmpty()->length(2, 100),
            'discreetData' => v::notEmpty()->length(2, 100),
            'totalAmount' => v::notEmpty()->numericVal()->positive(),
            'taxType' => v::notEmpty()->length(2, 30),
            'amountTax' => v::numericVal()->finite(),
            'detailedAmountType' => v::notEmpty()->length(2, 100),
            'detailedAmount' => v::numericVal()->finite(),
            'reference' => v::notEmpty()->length(1, 20),
            'installmentCount' => v::numericVal()->finite(),
        ];

        $errorMessages = [
            'terminalType' => 'El campo terminalType es requerido y debe ser un string.',
            'terminalId' => 'El campo terminalId es requerido y debe ser un string.',
            'acquirerId' => 'El campo acquirerId es requerido y debe ser un string.',
            'terminalTransactionId' => 'El campo terminalTransactionId es requerido y debe ser un número positivo.',
            'panCaptureMode' => 'El campo panCaptureMode es requerido y debe ser un string.',
            'pinCapability' => 'El campo pinCapability es requerido y debe ser un string.',
            'brand' => 'El campo brand es requerido y debe ser un string.',
            'trackData' => 'El campo trackData es requerido y debe ser un string.',
            'accountType' => 'El campo accountType es requerido y debe ser un string.',
            'tokenData' => 'El campo tokenData es requerido y debe ser un string.',
            'tokenStatus' => 'El campo tokenStatus es requerido y debe ser un string.',
            'discreetData' => 'El campo discreetData es requerido y debe ser un string.',
            'totalAmount' => 'El campo totalAmount es requerido y debe ser un número positivo.',
            'taxType' => 'El campo taxType es requerido y debe ser un string.',
            'amountTax' => 'El campo amountTax es requerido y debe ser un número finito.',
            'detailedAmountType' => 'El campo detailedAmountType es requerido y debe ser un string.',
            'detailedAmount' => 'El campo detailedAmount es requerido y debe ser un número finito.',
            'reference' => 'El campo reference es requerido y debe ser un string.',
            'installmentCount' => 'El campo installmentCount es requerido y debe ser un número entero finito.'
        ];

        return $this->validate($requestValidator, $errorMessages, $request);
    }

}
