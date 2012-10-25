<?php

namespace Cedriclombardot\OgonePaymentBundle\Batch;

class TransactionManager extends BatchManager
{
    const OPERATION_RES = 'RES';

    public function checkAuthorisation($amount, $brand, $card, $expirationDate, $customerName, $cvv, $orderId)
    {
        return $this->batchRequest->check(
            $this->buildTransactionCSVRow(self::OPERATION_RES, $amount, $brand, $card, $expirationDate, $customerName, $cvv, $orderId),
            'CHECKAUTH'.$orderId.'-'.time()
        );
    }

    public function requestAuthorisation($amount, $brand, $card, $expirationDate, $customerName, $cvv, $orderId)
    {
        return $this->batchRequest->process(
            $this->buildTransactionCSVRow(self::OPERATION_RES, $amount, $brand, $card, $expirationDate, $customerName, $cvv, $orderId),
            'AUTH'.$orderId.'-'.time()
        );
    }

    protected function buildTransactionCSVRow($operation, $amount, $brand, $cardNumber, $expirationDate, $customerName, $cvv, $orderId)
    {
        return strtr('%amount%;%currency%;%brand%;%cardNumber%;%expirationDate%;%orderId%;;%customerName%;;%operation%;;;;%pspid%;;;;;;;%cvv%;;;', array(
            '%operation%'      => $operation,
            '%amount%'         => $amount,
            '%currency%'       => $this->configurationContainer->get('CURRENCY'),
            '%brand%'          => $brand,
            '%cardNumber%'     => $cardNumber,
            '%expirationDate%' => $expirationDate,
            '%orderId%'        => $orderId,
            '%customerName%'   => $customerName,
            '%cvv%'            => $cvv,
            '%pspid%'          => $this->configurationContainer->get('PSPID'),
        ));
    }

}