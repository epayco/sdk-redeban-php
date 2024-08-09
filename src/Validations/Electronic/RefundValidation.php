<?php

namespace Epayco\SdkRedeban\Validations\Electronic;

use Epayco\SdkRedeban\Validations\Validation;
use Respect\Validation\Validator as v;

class RefundValidation extends Validation
{
    protected const TERMINAL_TYPE = ['WEB'];
    protected const CARD_TYPE = ['idTarjetaCredito', 'idTarjetaDebito'];
    protected const FRANCHISE = ['MasterCard', 'VISA', 'AmEx', 'DinersClub', 'Codensa', 'EPM', 'UnionPay', 'CMRFalabella', 'JCB', 'sociosbbva'];
    protected const PERSON_DOCUMENT_TYPE = ['NI', 'PS', 'CC', 'CE'];
    protected const PAN_CAPTURE_MODE = ['Manual', 'Registrado', 'Almacenado'];
    protected const PIN_CAPABILITY = ['Desconocido', 'Permitido', 'NoPermitidoBatch', 'Virtual'];

    public function __invoke($request)
    {
        $requestValidator = [
            'terminalType' => v::notEmpty()->in(self::TERMINAL_TYPE),
            'terminalId' => v::notEmpty()->length(2, 100),
            'acquirerId' => v::notEmpty()->length(2, 100),
            'terminalTransactionId' => v::notEmpty()->numericVal()->positive(),
            'panCaptureMode' => v::notEmpty()->in(self::PAN_CAPTURE_MODE, true),
            'pinCapability' => v::notEmpty()->in(self::PIN_CAPABILITY, true),
            'totalAmount' => v::notEmpty()->numericVal()->positive(),
            'ivaTax' => v::notEmpty()->numericVal()->positive(),
            'baseTax' => v::notEmpty()->numericVal()->positive(),
            'reference' => v::notEmpty()->length(1, 100),
            'instalmentsQuantity' => v::notEmpty()->numericVal()->finite(),
            'paymentIndicator' => v::optional(v::stringVal()->length(0, 100)),
            'paymentType' => v::optional(v::stringVal()->length(0, 100)),
            'recurringAmountType' => v::optional(v::stringVal()->length(0, 100)),
            'cardType' => v::notEmpty()->stringVal()->in(self::CARD_TYPE, true),
            'franchise' => v::notEmpty()->in(self::FRANCHISE),
            'cardNumber' => v::notEmpty()->length(10, 19),
            'expirationDate' => v::notEmpty()->length(5, 10)->regex('/^(202[4-9]|20[3-9]\d|21\d{2})-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/'),
            'securityCode' => v::optional(v::stringVal()->length(0, 6)),
            'personDocumentType' => v::optional(v::stringVal()->in(self::PERSON_DOCUMENT_TYPE, true)),
            'personDocumentNumber' => v::optional(v::stringVal()->length(0, 20)),
            'refundApprovalNumber' => v::notEmpty()->stringVal()->length(2, 50),
            'refundAuthorizerTransactionId' => v::notEmpty()->numericVal()->positive(),
            'infoPersonAddress' => v::optional(v::stringVal()->length(0, 200)),
            'infoPersonCity' => v::optional(v::stringVal()->length(0, 150)),
            'infoPersonDepartment' => v::optional(v::stringVal()->length(0, 5)),
            'infoPersonCommerceEmail' => v::optional(v::stringVal()->length(0, 300)->email()),
            'infoPersonPhone' => v::optional(v::stringVal()->length(0, 30)),
        ];

        $errorMessages = [
            'terminalType' => 'El campo terminalType es requerido y debe tener uno de los siguientes valores: ' . implode(', ', self::TERMINAL_TYPE),
            'terminalId' => 'El campo terminalId es requerido y debe ser un string.',
            'acquirerId' => 'El campo acquirerId es requerido y debe ser un string.',
            'terminalTransactionId' => 'El campo terminalTransactionId es requerido y debe ser un número positivo.',
            'panCaptureMode' => 'El campo panCaptureMode es requerido y debe tener uno de los siguientes valores: ' . implode(', ', self::PAN_CAPTURE_MODE),
            'pinCapability' => 'El campo pinCapability es requerido y debe tener uno de los siguientes valores: ' . implode(', ', self::PIN_CAPABILITY),
            'totalAmount' => 'El campo totalAmount es requerido y debe ser un número positivo.',
            'ivaTax' => 'El campo ivaTax es requerido y debe ser un número mayor o igual a 0.',
            'baseTax' => 'El campo baseTax es requerido y debe ser un número mayor o igual a 0.',
            'reference' => 'El campo reference es requerido',
            'instalmentsQuantity' => 'El campo instalmentsQuantity es requerido y debe ser un número entero finito.',
            'paymentIndicator' => 'El campo paymentIndicator debe ser un string.',
            'paymentType' => 'El campo paymentType debe ser un string.',
            'recurringAmountType' => 'El campo recurringAmountType debe ser un string',
            'cardType' => 'El campo paymentAccountType es requerido y debe tener uno de los siguientes valores: ' . implode(', ', self::CARD_TYPE),
            'franchise' => 'El campo franchise es requerido y debe tener uno de los siguientes valores: ' . implode(', ', self::FRANCHISE),
            'cardNumber' => 'El campo cardNumber es requerido y debe ser un string.',
            'expirationDate' => 'El campo expirationDate es requerido y debe tener el formato YYYY-MM-DD.',
            'securityCode' => 'El campo securityCode debe ser un string.',
            'personDocumentType' => 'El campo personDocumentType debe tener uno de los siguientes valores: ' . implode(', ', self::PERSON_DOCUMENT_TYPE),
            'personDocumentNumber' => 'El campo personDocumentNumber debe ser un string.',
            'refundApprovalNumber' => 'El campo refundApprovalNumber es requerido y debe ser un string.',
            'refundAuthorizerTransactionId' => 'El campo refundAuthorizerTransactionId es requerido y debe ser un número entero.',
            'infoPersonAddress' => 'El campo infoPersonAddress debe ser un string.',
            'infoPersonCity' => 'El campo infoPersonCity debe ser un string.',
            'infoPersonDepartment' => 'El campo infoPersonDepartment debe ser un string.',
            'infoPersonCommerceEmail' => 'El campo infoPersonCommerceEmail debe tener formato de email.',
            'infoPersonPhone' => 'El campo infoPersonPhone debe ser un string.',
            'infoPersonCellphone' => 'El campo infoPersonCellphone debe ser un string.',
        ];

        return $this->validate($requestValidator, $errorMessages, $request);
    }

}
