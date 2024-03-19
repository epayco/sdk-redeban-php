<?php
namespace Epayco\SdkRedeban\validations;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;
class Validation
{
    public $response;
    public function validate($validationRules, $errorMessages, $request)
    {
        $this->response = $request;
        foreach ($validationRules as $field => $validator) {
            try {
                $validator->setName($field)->assert($request->$field ?? null);
            } catch (ValidationException $e) {
                $errorMessage = $errorMessages[$field];
                $this->response = $errorMessage;
                return false;
            }
        }
        return true;
    }

}