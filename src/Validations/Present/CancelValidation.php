<?php

namespace Epayco\SdkRedeban\Validations\Present;

use Epayco\SdkRedeban\Validations\Validation;
use Respect\Validation\Validator as v;

class CancelValidation extends Validation
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
            'reference' => v::notEmpty()->length(1, 20),
            'instalmentCount' => v::numericVal()->finite(),
            'approvalNumber' => v::notEmpty()->length(1, 70),
            'transactionIdAuthorizer' => v::notEmpty()->length(1, 70),
            'additionalData' => v::arrayType(),
            'infoTax' => v::arrayType(),
            'detailedAmount' => v::arrayType(),
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
            'reference' => 'El campo reference es requerido y debe ser un string.',
            'instalmentCount' => 'El campo instalmentCount es requerido y debe ser un número entero finito.',
            'approvalNumber' => 'El campo approvalNumber es requerido y debe ser un string.',
            'transactionIdAuthorizer' => 'El campo transactionIdAuthorizer es requerido y debe ser un string.',
            'additionalData' => 'El campo adicionalData debe ser un array de objetos.',
            'infoTax' => 'El campo infoTax debe ser un array de objetos.',
            'detailedAmount' => 'El campo detailedAmount debe ser un array de objetos.',
        ];

        return $this->validate($requestValidator, $errorMessages, $request);
    }

}
