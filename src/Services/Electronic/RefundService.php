<?php
namespace Epayco\SdkRedeban\Services\Electronic;

use Epayco\SdkRedeban\Helpers\UtilsElectronicPurchase;
use Epayco\SdkRedeban\Repositories\PurchaseElectronicRepository;
use Epayco\SdkRedeban\Services\Service;
use Exception;
use stdClass;

class RefundService extends Service
{
    use UtilsElectronicPurchase;
    public mixed $outData;
    public mixed $logs;
    public function refund($inputData): bool
    {
        $restFinalPos = [];
        $obj = json_decode(json_encode($inputData));
        $refundRequest = $this->generateCancelRequest($obj);
        try {
            $maskedCardNumber = $this->maskCardNumber($obj->cardNumber);
            $redebanRepository = new PurchaseElectronicRepository();
            $refundResponse = $redebanRepository->refund($refundRequest, $maskedCardNumber);
            $providerResponse = $refundResponse->providerResponse ?? null;
            $restFinalPos = (array)$providerResponse ?? [];

            $status = isset($providerResponse->infoRespuesta->codRespuesta) && $providerResponse->infoRespuesta->codRespuesta == '00';

            $refundResponse->logs->request = $this->cleanRequest($maskedCardNumber, $obj->cardType ?? '', $refundRequest);
        } catch(Exception $e) {
            $providerResponse = $e->getMessage();
            $status = false;
        }
        $this->logs = $refundResponse->logs ?? $providerResponse ?? null;
        $this->outData = $restFinalPos;

        return $status;

    }

    public function generateCancelRequest($obj): stdClass
    {
        $compraCancelacionProcesarSolicitud = new stdClass();

        $compraCancelacionProcesarSolicitud->cabeceraSolicitud = new stdClass();
        $compraCancelacionProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion = new stdClass();
        $compraCancelacionProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion->tipoTerminal = $obj->terminalType;
        $compraCancelacionProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion->idTerminal = $obj->terminalId;
        $compraCancelacionProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion->idAdquiriente = $obj->acquirerId;
        $compraCancelacionProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion->idTransaccionTerminal = $obj->terminalTransactionId;
        $compraCancelacionProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion->modoCapturaPAN = $obj->panCaptureMode;
        $compraCancelacionProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion->capacidadPIN = $obj->pinCapability;

        $compraCancelacionProcesarSolicitud->idPersona = new stdClass();
        $compraCancelacionProcesarSolicitud->idPersona->tipoDocumento = $obj->personDocumentType;
        $compraCancelacionProcesarSolicitud->idPersona->numDocumento = $obj->personDocumentNumber;

        $compraCancelacionProcesarSolicitud->infoMedioPago = new stdClass();
        $compraCancelacionProcesarSolicitud->infoMedioPago->{$obj->cardType} = new stdClass();
        $compraCancelacionProcesarSolicitud->infoMedioPago->{$obj->cardType}->franquicia = $obj->franchise;
        $compraCancelacionProcesarSolicitud->infoMedioPago->{$obj->cardType}->numTarjeta = $obj->cardNumber;
        $compraCancelacionProcesarSolicitud->infoMedioPago->{$obj->cardType}->fechaExpiracion = $obj->expirationDate;
        $compraCancelacionProcesarSolicitud->infoMedioPago->{$obj->cardType}->codVerificacion = $obj->securityCode;

        $compraCancelacionProcesarSolicitud->infoCompra = new stdClass();
        $compraCancelacionProcesarSolicitud->infoCompra->montoTotal = $obj->totalAmount;

        if ($obj->ivaTax > 0) {
            $compraCancelacionProcesarSolicitud->infoCompra->infoImpuestos = new stdClass();
            $compraCancelacionProcesarSolicitud->infoCompra->infoImpuestos->tipoImpuesto = 'IVA';
            $compraCancelacionProcesarSolicitud->infoCompra->infoImpuestos->monto = $obj->ivaTax;
            $compraCancelacionProcesarSolicitud->infoCompra->infoImpuestos->baseImpuesto = $obj->baseTax;
        }

        if ($obj->infoPersonAddress) {
            $compraCancelacionProcesarSolicitud->infoPersona = new stdClass();
            $compraCancelacionProcesarSolicitud->infoPersona->direccion = $obj->infoPersonAddress;
            $compraCancelacionProcesarSolicitud->infoPersona->ciudad = $obj->infoPersonCity;
            $compraCancelacionProcesarSolicitud->infoPersona->departamento = $obj->infoPersonDepartment;
            $compraCancelacionProcesarSolicitud->infoPersona->emailComercio = $obj->infoPersonCommerceEmail;
            $compraCancelacionProcesarSolicitud->infoPersona->telefonoFijo = $obj->infoPersonPhone;
            $compraCancelacionProcesarSolicitud->infoPersona->celular = $obj->infoPersonCellphone;
        }

        $compraCancelacionProcesarSolicitud->infoRefCancelacion = new stdClass();
        $compraCancelacionProcesarSolicitud->infoRefCancelacion->numAprobacion = $obj->refundApprovalNumber;
        $compraCancelacionProcesarSolicitud->infoRefCancelacion->idTransaccionAutorizador = $obj->refundAuthorizerTransactionId;

        return $compraCancelacionProcesarSolicitud;
    }

    private function maskCardNumber(string $cardNumber): string
    {
        return substr($cardNumber, 0, 6) . substr($cardNumber, -4);
    }
}
