<?php
namespace Epayco\SdkRedeban\Services\Apisac;

use Epayco\SdkRedeban\Helpers\Apisac\ApiSacConfig;
use Epayco\SdkRedeban\Helpers\Apisac\EncryptionManager;
use Epayco\SdkRedeban\Helpers\Apisac\RSAEncryption;
use Epayco\SdkRedeban\Repositories\ApiSacRepository;
use Epayco\SdkRedeban\Services\Service;
use Exception;
use stdClass;

class RefundService extends Service
{
    public mixed $outData;
    public mixed $logs;
    private ApiSacConfig $sdkConfig;
    private const TOTAL_ADJUSTMENT_TYPE = 'T';
    private const PARTIAL_ADJUSTMENT_TYPE = 'P';
    public function refund($inputData): bool
    {
        $restFinalPos = [];
        $obj = json_decode(json_encode($inputData));
        $refundRequest = $this->generateRefundRequest($obj);
        $encryptedRequest = $this->encryptRequest($refundRequest);
        try {
            $redebanRepository = new ApiSacRepository();
            $refundResponse = $redebanRepository->refund($encryptedRequest);
            $providerResponse = $refundResponse->providerResponse ?? null;
            $restFinalPos = (array)$providerResponse ?? [];

            $status = isset($providerResponse->infoRespuesta->codRespuesta) && $providerResponse->infoRespuesta->codRespuesta == '00';
        } catch(Exception $e) {
            $providerResponse = $e->getMessage();
            $status = false;
        }
        $this->logs = $refundResponse->logs ?? $providerResponse ?? null;
        $this->outData = $restFinalPos;

        return $status;

    }

    public function generateRefundRequest($obj): stdClass
    {
        $this->sdkConfig = ApiSacConfig::getInstance();
        $apiSacConfig = $this->sdkConfig->getConfig();

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
        $refundRequest->IDPasarela = $apiSacConfig->redebanClientId;

        return $refundRequest;
    }

    public function encryptRequest(mixed $request)
    {
        $rsaEncryption = new RSAEncryption();
        $encryptionManager = new EncryptionManager($rsaEncryption);
        return $encryptionManager->encryptData($request, []);
    }

}
