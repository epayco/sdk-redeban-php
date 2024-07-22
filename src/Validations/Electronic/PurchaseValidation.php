<?php

namespace Epayco\SdkRedeban\Validations\Electronic;

use Epayco\SdkRedeban\Validations\Validation;
use Respect\Validation\Validator as v;

class PurchaseValidation extends Validation
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
            'totalAmount' => v::notEmpty()->numericVal()->positive(),
            'ivaTax' => v::notEmpty()->numericVal()->positive(),
            'baseTax' => v::notEmpty()->numericVal()->positive(),
            'reference' => v::notEmpty()->length(1, 100),
            'instalmentsQuantity' => v::notEmpty()->numericVal()->finite(),
            'paymentIndicator' => v::stringVal()->length(1, 100),
            'recurringAmountType' => v::stringVal()->length(1, 100),
            'paymentAccountType' => v::stringVal()->length(1, 100),
            'franchise' => v::notEmpty()->length(2, 40),
            'cardNumber' => v::notEmpty()->length(10, 19),
            'expirationDate' => v::notEmpty()->length(5, 8),
            'securityCode' => v::stringVal()->length(3, 6),
            'personDocumentType' => v::stringVal()->length(2, 3),
            'personDocumentNumber' => v::stringVal()->length(2, 20),
            'additionalInfoEci' => v::stringVal()->length(1, 100),
            'directoryServerTransactionId' => v::stringVal()->length(1, 700),
            'secVersion' => v::stringVal()->length(1, 10),
            'acctAuthValue' => v::stringVal()->length(1, 100),
            'marcTerminal' => v::stringVal()->length(1, 100),
            'facilitatorId' => v::stringVal()->length(1, 100),
            'salesOrgId' => v::stringVal()->length(1, 100),
            'subMerchId' => v::stringVal()->length(1, 30),
        ];

        $errorMessages = [
            'terminalType' => 'El campo terminalType es requerido y debe ser un string.',
            'terminalId' => 'El campo terminalId es requerido y debe ser un string.',
            'acquirerId' => 'El campo acquirerId es requerido y debe ser un string.',
            'terminalTransactionId' => 'El campo terminalTransactionId es requerido y debe ser un número positivo.',
            'panCaptureMode' => 'El campo panCaptureMode es requerido y debe ser un string.',
            'pinCapability' => 'El campo pinCapability es requerido y debe ser un string.',
            'totalAmount' => 'El campo totalAmount es requerido y debe ser un número positivo.',
            'ivaTax' => 'El campo ivaTax es requerido y debe ser un número mayor o igual a 0.',
            'baseTax' => 'El campo baseTax es requerido y debe ser un número mayor o igual a 0.',
            'reference' => 'El campo reference es requerido',
            'instalmentsQuantity' => 'El campo instalmentsQuantity es requerido y debe ser un número entero finito.',
            'paymentIndicator' => 'El campo discreetData es requerido y debe ser un string.',
            'recurringAmountType' => 'El campo totalAmount es requerido y debe ser un número positivo.',
            'paymentAccountType' => 'El campo reference es requerido y debe ser un string.',
            'franchise' => 'El campo frachise es requerido y debe ser un string.',
            'cardNumber' => 'El campo cardNumber es requerido y debe ser un string.',
            'expirationDate' => 'El campo expirationDate es requerido y debe ser un string.',
            'securityCode' => 'El campo securityCode debe ser un string.',
            'personDocumentType' => 'El campo personDocumentType debe ser un string.',
            'personDocumentNumber' => 'El campo personDocumentNumber debe ser un string.',
            'additionalInfoEci' => 'El campo additionalInfoEci debe ser un string.',
            'directoryServerTransactionId' => 'El campo directoryServerTransactionId debe ser un string.',
            'secVersion' => 'El campo secVersion debe ser un string.',
            'acctAuthValue' => 'El campo acctAuthValue debe ser un string.',
            'marcTerminal' => 'El campo marcTerminal debe ser un string.',
            'facilitatorId' => 'El campo facilitatorId debe ser un string.',
            'salesOrgId' => 'El campo salesOrgId debe ser un string.',
            'subMerchId' => 'El campo subMerchId debe ser un string.',
        ];

        return $this->validate($requestValidator, $errorMessages, $request);
    }

}
