<?php

namespace Epayco\SdkRedeban\Validations\Apisac;

use Epayco\SdkRedeban\Validations\Validation;
use Respect\Validation\Validator as v;

class RefundValidation extends Validation
{
    public function __invoke($request)
    {
        $requestValidator = [
            'transactionDate' => v::notEmpty()->date(),
            'acquirerId' => v::notEmpty()->length(2, 100),
            'cardNumber' => v::notEmpty()->length(4, 6),
            'totalAmount' => v::notEmpty()->floatVal()->positive(),
            'refundApprovalNumber' => v::notEmpty()->stringVal()->length(2, 50),
            'adjustmentAmount' => v::optional(v::floatVal()->positive()),
        ];

        $errorMessages = [
            'transactionDate' => 'El campo transactionDate es requerido y debe tener el formato Y-m-d.',
            'acquirerId' => 'El campo acquirerId es requerido y debe ser un string.',
            'cardNumber' => 'El campo cardNumber es requerido y debe tener mínimamente 4 caracteres.',
            'totalAmount' => 'El campo totalAmount es requerido y debe ser un número real.',
            'refundApprovalNumber' => 'El campo refundApprovalNumber es requerido y debe ser un string.',
            'adjustmentAmount' => 'El campo adjustmentAmount debe ser un número real.',
        ];

        return $this->validate($requestValidator, $errorMessages, $request);
    }

}
