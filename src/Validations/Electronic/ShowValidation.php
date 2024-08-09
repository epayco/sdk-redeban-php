<?php

namespace Epayco\SdkRedeban\Validations\Electronic;

use Epayco\SdkRedeban\Validations\Validation;
use Respect\Validation\Validator as v;

class ShowValidation extends Validation
{
    protected const TERMINAL_TYPE = ['WEB'];

    public function __invoke($request): bool
    {
        $requestValidator = [
            'terminalType' => v::notEmpty()->in(self::TERMINAL_TYPE),
            'terminalId' => v::notEmpty()->length(2, 100),
            'acquirerId' => v::notEmpty()->length(2, 100),
            'terminalTransactionId' => v::notEmpty()->numericVal()->positive(),
            'transactionDate' => v::notEmpty()->length(5, 10)->regex('/^(202[4-9]|20[3-9]\d|21\d{2})-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/'),
        ];

        $errorMessages = [
            'terminalType' => 'El campo terminalType es requerido y debe tener uno de los siguientes valores: ' . implode(', ', self::TERMINAL_TYPE),
            'terminalId' => 'El campo terminalId es requerido y debe ser un string.',
            'acquirerId' => 'El campo acquirerId es requerido y debe ser un string.',
            'terminalTransactionId' => 'El campo terminalTransactionId es requerido y debe ser un número positivo.',
            'transactionDate' => 'El campo transactionDate es requerido y debe tener el formato YYYY-MM-DD.',
        ];

        return $this->validate($requestValidator, $errorMessages, $request);
    }

}
