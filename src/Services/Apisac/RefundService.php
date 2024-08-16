<?php
namespace Epayco\SdkRedeban\Services\Apisac;

use Epayco\SdkRedeban\Helpers\Apisac\ApiSacConfig;
use Epayco\SdkRedeban\Helpers\Apisac\ApiSacParams;
use Epayco\SdkRedeban\Helpers\Apisac\EncryptionManager;
use Epayco\SdkRedeban\Helpers\Apisac\RSAEncryption;
use Epayco\SdkRedeban\Repositories\ApiSacRepository;
use Epayco\SdkRedeban\Services\Service;
use Exception;
use stdClass;

class RefundService extends Service
{
    use ApiSacParams;

    public mixed $outData;
    public mixed $logs;
    private const TOTAL_ADJUSTMENT_TYPE = 'T';
    private const PARTIAL_ADJUSTMENT_TYPE = 'P';
    public function refund($inputData): bool
    {
        $restFinalPos = [];
        $rsaEncryption = new RSAEncryption();
        $encryptionManager = new EncryptionManager($rsaEncryption);
        $obj = json_decode(json_encode($inputData));
        $refundRequest = $this->generateRefundRequest($obj);
        $encryptedRequest = $encryptionManager->encryptData(json_encode($refundRequest), null);
        $status = false;
        try {
            $redebanRepository = new ApiSacRepository();
            $refundResponse = $redebanRepository->refund($encryptedRequest);
            if (isset($refundResponse->success) && $refundResponse->success) {
                $decryptedData = $encryptionManager->decryptData($refundResponse->data, null);

                $restFinalPos = (array)$decryptedData ?? [];
                $this->logs = (object)[
                    'request' => $refundRequest,
                    'response' => $decryptedData,
                ];
                $status = isset($decryptedData->codRespuesta) && $decryptedData->codRespuesta == '00';
            }

        } catch(Exception $e) {
            $this->logs = $e->getMessage();
        }
        $this->outData = $restFinalPos;

        return $status;

    }

    public function generateRefundRequest($obj): stdClass
    {
        $sdkConfig = ApiSacConfig::getInstance();
        $apiSacConfig = $sdkConfig->getConfig();
        $apiSacParams = $this->getParams($apiSacConfig->environment);

        $refundRequest = new stdClass();

        $refundRequest->codigoUnico = $obj->transactionDate;
        $refundRequest->fechaTransaccion = $obj->acquirerId;
        $refundRequest->tarjeta = $obj->cardNumber;
        $refundRequest->valorOriginal = $obj->totalAmount;
        $refundRequest->numeroAprobacion = $obj->refundApprovalNumber;
        $refundRequest->tipoAjuste = self::TOTAL_ADJUSTMENT_TYPE;
        if (!empty($obj->adjustmentAmount)) {
            $refundRequest->valorAjuste = $obj->adjustmentAmount;
            $refundRequest->tipoAjuste = self::PARTIAL_ADJUSTMENT_TYPE;
        }
        $refundRequest->IDPasarela = $apiSacParams->clientId;

        return $refundRequest;
    }

}
