<?php

namespace Epayco\SdkRedeban\Services\Electronic;

use Epayco\SdkRedeban\Repositories\PurchaseElectronicRepository;
use Epayco\SdkRedeban\Services\Service;
use Exception;
use stdClass;

class PurchaseService extends Service
{
    public mixed $outData;
    public mixed $logs;
    public function purchase($data): bool
    {
        $restFinalPos = [];
        $obj = json_decode(json_encode($data));
        $request = $this->generatePurchaseRequest($obj);
        $redebanRepository = new PurchaseElectronicRepository();
        try {
            $maskedCardNumber = $this->maskCardNumber($obj->cardNumber);
            $purchaseResponse = $redebanRepository->purchase($request, $maskedCardNumber);
            $providerResponse = $purchaseResponse->providerResponse ?? null;
            $restFinalPos = (array)$providerResponse ?? [];

            $status = isset($providerResponse->infoRespuesta->codRespuesta) && $providerResponse->infoRespuesta->codRespuesta == '00';
        } catch(Exception $e) {
            $providerResponse = $e->getMessage();
            $status = false;
        }
        $this->logs = $purchaseResponse->logs ?? $providerResponse ?? null;
        $this->outData = $restFinalPos;

        return $status;
    }

    public function generatePurchaseRequest($obj): stdClass
    {
        $compraProcesarSolicitud = new stdClass();

        $compraProcesarSolicitud->cabeceraSolicitud = new stdClass();
        $compraProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion = new stdClass();
        $compraProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion->tipoTerminal = $obj->terminalType;
        $compraProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion->idTerminal = $obj->terminalId;
        $compraProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion->idAdquiriente = $obj->acquirerId;
        $compraProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion->idTransaccionTerminal = $obj->terminalTransactionId;
        $compraProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion->modoCapturaPAN = $obj->panCaptureMode;
        $compraProcesarSolicitud->cabeceraSolicitud->infoPuntoInteraccion->capacidadPIN = $obj->pinCapability;

        $compraProcesarSolicitud->idPersona = new stdClass();
        $compraProcesarSolicitud->idPersona->tipoDocumento = $obj->personDocumentType;
        $compraProcesarSolicitud->idPersona->numDocumento = $obj->personDocumentNumber;

        $compraProcesarSolicitud->infoMedioPago = new stdClass();
        $compraProcesarSolicitud->infoMedioPago->{$obj->cardType} = new stdClass();
        $compraProcesarSolicitud->infoMedioPago->{$obj->cardType}->franquicia = $obj->franchise;
        $compraProcesarSolicitud->infoMedioPago->{$obj->cardType}->numTarjeta = $obj->cardNumber;
        $compraProcesarSolicitud->infoMedioPago->{$obj->cardType}->fechaExpiracion = $obj->expirationDate;
        $compraProcesarSolicitud->infoMedioPago->{$obj->cardType}->codVerificacion = $obj->securityCode;

        $compraProcesarSolicitud->infoCompra = new stdClass();
        $compraProcesarSolicitud->infoCompra->montoTotal = $obj->totalAmount;
        $compraProcesarSolicitud->infoCompra->referencia = $obj->reference;
        $compraProcesarSolicitud->infoCompra->cantidadCuotas = $obj->instalmentsQuantity;

        if ($obj->ivaTax > 0) {
            $compraProcesarSolicitud->infoCompra->infoImpuestos = new stdClass();
            $compraProcesarSolicitud->infoCompra->infoImpuestos->tipoImpuesto = 'IVA';
            $compraProcesarSolicitud->infoCompra->infoImpuestos->monto = $obj->ivaTax;
            $compraProcesarSolicitud->infoCompra->infoImpuestos->baseImpuesto = $obj->baseTax;
        }

        $compraProcesarSolicitud->infoAdicional = new stdClass();
        if ($obj->eci) {
            $compraProcesarSolicitud->infoAdicional->eci = $obj->threeDSEci;
            $compraProcesarSolicitud->infoAdicional->directoryServerTrxID = $obj->threeDSDirectoryServerTransactionId;
            $compraProcesarSolicitud->infoAdicional->secVersion = $obj->threeDSSecVersion;
            $compraProcesarSolicitud->infoAdicional->acctAuthValue = $obj->threeDSAcctAuthValue;
        }

        if ($obj->paymentIndicator) {
            $compraProcesarSolicitud->infoAdicional->infoPago = new stdClass();
            $compraProcesarSolicitud->infoAdicional->infoPago->indicadorPago = $obj->paymentIndicator;
            $compraProcesarSolicitud->infoAdicional->infoPago->tipoPago = $obj->paymentType;
            $compraProcesarSolicitud->infoAdicional->infoPago->tipoMontoRecurrente = $obj->recurringAmountType;
        }

        if ($obj->softDescMarcTerminal) {
            $compraProcesarSolicitud->infoCompra->infoFacilitador = new stdClass();
            $compraProcesarSolicitud->infoCompra->infoFacilitador->marcTerminal = $obj->softDescMarcTerminal;
            $compraProcesarSolicitud->infoCompra->infoFacilitador->FacilitadorID = $obj->softDescFacilitatorId;
            $compraProcesarSolicitud->infoCompra->infoFacilitador->SalesOrgID = $obj->softDescSalesOrgId;
            $compraProcesarSolicitud->infoCompra->infoFacilitador->SubMerchID = $obj->softDescSubMerchId;
        }

        if ($obj->softDescMarcTerminal) {
            $compraProcesarSolicitud->infoCompra->infoFacilitador = new stdClass();
            $compraProcesarSolicitud->infoCompra->infoFacilitador->marcTerminal = $obj->softDescMarcTerminal;
            $compraProcesarSolicitud->infoCompra->infoFacilitador->FacilitadorID = $obj->softDescFacilitatorId;
            $compraProcesarSolicitud->infoCompra->infoFacilitador->SalesOrgID = $obj->softDescSalesOrgId;
            $compraProcesarSolicitud->infoCompra->infoFacilitador->SubMerchID = $obj->softDescSubMerchId;
        }

        if ($obj->infoPersonAddress) {
            $compraProcesarSolicitud->infoPersona = new stdClass();
            $compraProcesarSolicitud->infoPersona->direccion = $obj->infoPersonAddress;
            $compraProcesarSolicitud->infoPersona->ciudad = $obj->infoPersonCity;
            $compraProcesarSolicitud->infoPersona->departamento = $obj->infoPersonDepartment;
            $compraProcesarSolicitud->infoPersona->emailComercio = $obj->infoPersonCommerceEmail;
            $compraProcesarSolicitud->infoPersona->telefonoFijo = $obj->infoPersonPhone;
            $compraProcesarSolicitud->infoPersona->celular = $obj->infoPersonCellphone;
        }

        if ($obj->softDescMarcTerminal) {
            $compraProcesarSolicitud->infoCompra->infoFacilitador = new stdClass();
            $compraProcesarSolicitud->infoCompra->infoFacilitador->marcTerminal = $obj->softDescMarcTerminal;
            $compraProcesarSolicitud->infoCompra->infoFacilitador->FacilitadorID = $obj->softDescFacilitatorId;
            $compraProcesarSolicitud->infoCompra->infoFacilitador->SalesOrgID = $obj->softDescSalesOrgId;
            $compraProcesarSolicitud->infoCompra->infoFacilitador->SubMerchID = $obj->softDescSubMerchId;
        }

        return $compraProcesarSolicitud;
    }

    private function maskCardNumber(string $cardNumber): string
    {
        return substr($cardNumber, 0, 6) . substr($cardNumber, -4);
    }

}
