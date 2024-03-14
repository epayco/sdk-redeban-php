<?php

namespace Epayco\SdkRedeban\services;

use Epayco\SdkRedeban\services\Service;
use Epayco\SdkRedeban\repositories\RedebanRepository;
use Epayco\SdkRedeban\validations\VoidValidation;

class VoidService extends Service
{
    public $outData = [];
    public array $inData = [];
    public bool $status = false;

    public function __invoke(VoidValidation $VoidValidation)
    {
        $this->inData = $VoidValidation->response;
        $this->process();
        return $this->status;
    }
    public function process()
    {
        $redebanRepository = new RedebanRepository();
        $rest = $redebanRepository->void($this->inData);
        $restPos=$rest['soapenv:Body']['com:compraCancelacionProcesarRespuesta'];
        $restFinalPos['cod']=$restPos['com:infoRespuesta']['esb:codRespuesta'];
        $restFinalPos['descRes']=$restPos['com:infoRespuesta']['esb:estado'];
        $restFinalPos['status']=$restPos['com:infoRespuesta']['esb:descRespuesta'];
        $this->outData=$restFinalPos;
        $this->status = true;
    }

}
