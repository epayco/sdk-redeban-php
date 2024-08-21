<?php
namespace Epayco\SdkRedeban\Services\Present;

use Epayco\SdkRedeban\Helpers\UtilsPresentSales;
use Epayco\SdkRedeban\Repositories\RedebanRepository;
use Epayco\SdkRedeban\Services\Service;
use Exception;

class ReverseService extends Service
{
    use UtilsPresentSales;
    protected const MAX_RETRY_REVERSE_REDEBAN = 3;
    public mixed $outData;
    public function __invoke($inputData): bool
    {
        $restFinalPos = [];
        $obj = json_decode(json_encode($inputData));
        $purchaseService = new ShopService();
        $reverseRequest = $purchaseService->generateRequestShop($obj);
        $retry = 1;
        try {
            $redebanRepository = new RedebanRepository();
            do {
                $redebanResponse = $redebanRepository->reverse($reverseRequest);
                $restFinalPos = (array)$redebanResponse;
                $status = isset($redebanResponse->infoRespuesta->codRespuesta) && $redebanResponse->infoRespuesta->codRespuesta == '00';
                $retry++;
            } while (!$status && $retry < self::MAX_RETRY_REVERSE_REDEBAN);

        } catch(Exception $e) {
            $redebanResponse = $e;
            $status = false;
        }
        $restFinalPos['log_request']    = $this->removeCardData($reverseRequest);
        $restFinalPos['log_response']   = $redebanResponse ?? null;
        $this->outData = $restFinalPos;

        return $status;
        
    }
}
