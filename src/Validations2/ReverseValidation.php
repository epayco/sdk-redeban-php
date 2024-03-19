<?php

namespace Epayco\SdkRedeban\validations;

use Epayco\SdkRedeban\Validations\Validation;
use Respect\Validation\Validator as v;

class ReverseValidation extends Validation
{

    public function __invoke($request)
    {
        $validationRules = [
            'terminalType' => v::notEmpty(),
            'terminalId' => v::notEmpty(),
            'acquirerId' => v::notEmpty(),
            'terminalTransactionId' => v::notEmpty(),
            'panCaptureMode' => v::notEmpty(),
            'pinCapability' => v::notEmpty(),
            'brand' => v::notEmpty(),
            'trackData' => v::notEmpty(),
            'accountType' => v::notEmpty(),
            'tokenData' => v::notEmpty(),
            'discreetData' => v::notEmpty(),
            'tokenStatus' => v::notEmpty(),
            'totalAmount' => v::notEmpty()->numericVal()->positive(),
            'taxInfo' => v::arrayType()->notEmpty(),
            'detailedAmount' => v::arrayType()->notEmpty(),
            'reference' => v::notEmpty()->length(6, 10),
            'qtyFee' => v::notEmpty()->intVal()->positive(),
            'additionalData' => v::arrayType()->notEmpty(),
        ];

        $errorMessages = [
            'terminalType' => 'El tipo de terminal no puede estar vacío.',
            'terminalId' => 'El ID de terminal no puede estar vacío.',
            'acquirerId' => 'El ID del adquirente no puede estar vacío.',
            'terminalTransactionId' => 'El ID de transacción del terminal no puede estar vacío.',
            'panCaptureMode' => 'El modo de captura PAN no puede estar vacío.',
            'pinCapability' => 'La capacidad de PIN no puede estar vacía.',
            'brand' => 'La marca no puede estar vacía.',
            'trackData' => 'Los datos de la pista no pueden estar vacíos.',
            'accountType' => 'El tipo de cuenta no puede estar vacío.',
            'tokenData' => 'Los datos del token no pueden estar vacíos.',
            'discreetData' => 'Los datos discretos no pueden estar vacíos.',
            'tokenStatus' => 'El estado del token no puede estar vacío.',
            'totalAmount' => 'El monto total debe ser un número positivo.',
            'taxInfo' => 'La información de impuestos debe ser un array no vacío.',
            'detailedAmount' => 'La cantidad detallada debe ser un array no vacío.',
            'reference' => 'La referencia debe tener entre 6 y 10 caracteres.',
            'qtyFee' => 'La cantidad de tarifas debe ser un número entero positivo.',
            'additionalData' => 'Los datos adicionales deben ser un array no vacío.',
        ];

        return $this->validate($validationRules, $errorMessages, $request);
    }
    
}
