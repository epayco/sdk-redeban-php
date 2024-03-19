<?php
namespace Epayco\SdkRedeban\Validations;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;
class Validation
{
    public $response;
    public function validate($requestValidator, $errorMessages, $request)
    {
        $this->response = $request;
        foreach ($requestValidator as $field => $validator) {
            try {
                $validator->setName($field)->assert($request->$field);
            } catch (ValidationException $e) {
                $errorMessage = $errorMessages[$field];
                $this->response = $errorMessage;
                return false;
            }
        }
        return true;
    }

}