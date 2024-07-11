<?php

namespace Epayco\SdkRedeban\Validations;

use Epayco\SdkRedeban\Validations\Validation;
use Respect\Validation\Validator as v;

class VoidValidation extends Validation
{

    public function __invoke($request)
    {
        $requestValidator = [
            'terminalId' => v::notEmpty()->length(2, 100),
            'acquirerId' => v::notEmpty()->length(2, 100),
            'terminalTransactionId' => v::notEmpty()->length(2, 100),
            'panCaptureMode' => v::notEmpty()->length(2, 100),
            'pinCapability' => v::notEmpty()->length(2, 100),
            'franchise' => v::notEmpty()->length(2, 100),
            'track' => v::notEmpty()->length(2, 100),
            'accountType' => v::stringVal()->notEmpty()->length(2, 100),
            'tokenData' => v::notEmpty()->length(2, 100),
            'tokenStatus' => v::notEmpty()->length(2, 100),
            'discreteData' => v::notEmpty()->length(2, 100),
            'totalAmount' => v::notEmpty()->numericVal()->positive(),
            'taxAmount' => v::numericVal()->finite(),
            'amountBase' => v::numericVal()->finite(),
            'VATRefundBase' => v::numericVal()->finite(),
            'reference' => v::notEmpty()->length(2, 100),
            'instalmentCount' => v::numericVal()->finite(),
            'authorizerTransactionId' => v::notEmpty()->length(2, 100),
        ];

        $errorMessages = [
            'terminalId' => 'El campo terminalId es requerido y debe ser un string.',
            'acquirerId' => 'El campo acquirerId es requerido y debe ser un string.',
            'terminalTransactionId' => 'El campo terminalTransactionId es requerido y debe ser un string.',
            "panCaptureMode" => 'El campo panCaptureMode es requerido y debe ser un string.',
            "pinCapability" => 'El campo pinCapability es requerido y debe ser un string.',
            "track" => 'El campo track es requerido y debe ser un string.',
            "accountType" => 'El campo accountType es requerido y debe ser un string.',
            "tokenData" => 'El campo tokenData es requerido y debe ser un string.',
            "discreteData" => 'El campo discreteData es requerido y debe ser un string.',
            "tokenStatus" => 'El campo tokenStatus es requerido y debe ser un string.',
            "totalAmount" => 'El campo totalAmount es requerido y debe ser un número positivo.',
            "taxAmount" => 'El campo taxAmount es requerido y debe ser un número positivo.',
            "amountBase" => 'El campo amountBase es requerido y debe ser un número positivo.',
            "VATRefundBase" => 'El campo VATRefundBase es requerido y debe ser un número positivo.',
            "reference" => 'El campo reference es requerido y debe ser un string.',
            "instalmentCount" => 'El campo instalmentCount es requerido y debe ser un número entero positivo.',
            "approvalNumber" => 'El campo approvalNumber es requerido',
            "authorizerTransactionId" => 'El campo authorizerTransactionId es requerido',
        ];

        return $this->validate($requestValidator, $errorMessages, $request);
    }

}
