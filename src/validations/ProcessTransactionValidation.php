<?php
namespace Epayco\SdkRedeban\validations;
use Epayco\SdkRedeban\validations\Validation;


class ProcessTransactionValidation extends Validation
{
    static public function validate($data)
    {
        return ["success"=> true, "data" => $data];
    }
}