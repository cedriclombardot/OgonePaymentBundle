<?php

namespace Pilot\OgonePaymentBundle\Batch;

class TransactionManager extends BatchManager
{
    const OPERATION_RES = 'RES';
    const OPERATION_SAL = 'SAL';

    public function checkAuthorisation($amount, $brand, $card, $expirationDate, $customerName, $cvv, $orderId, $aliasName = '')
    {
        return $this->batchRequest->check(
            $this->buildTransactionCSVRow(self::OPERATION_RES, $amount, $brand, $card, $expirationDate, $customerName, $cvv, $orderId, $aliasName),
            'CHECKAUTH'.$orderId.'-'.time()
        );
    }

    public function requestAuthorisation($amount, $brand, $card, $expirationDate, $customerName, $cvv, $orderId, $aliasName = '')
    {
        return $this->batchRequest->process(
            $this->buildTransactionCSVRow(self::OPERATION_RES, $amount, $brand, $card, $expirationDate, $customerName, $cvv, $orderId, $aliasName),
            'AUTH'.$orderId.'-'.time()
        );
    }

    public function requestSale($amount, $brand, $card, $expirationDate, $customerName, $cvv, $orderId, $aliasName = '')
    {
        return $this->batchRequest->process(
            $this->getSaleCsvRow($amount, $brand, $card, $expirationDate, $customerName, $cvv, $orderId, $aliasName),
            'SAL'.$orderId.'-'.time()
        );
    }

    public function getSaleCsvRow($amount, $brand, $card, $expirationDate, $customerName, $cvv, $orderId, $aliasName = '')
    {
        return $this->buildTransactionCSVRow(self::OPERATION_SAL, $amount, $brand, $card, $expirationDate, $customerName, $cvv, $orderId, $aliasName);
    }

    protected function buildTransactionCSVRow($operation, $amount, $brand, $cardNumber, $expirationDate, $customerName, $cvv, $orderId, $aliasName = '')
    {
        return strtr('%amount%;%currency%;%brand%;%cardNumber%;%expirationDate%;%orderId%;;%customerName%;;%operation%;;;;%pspid%;;;%aliasName%;;;;%cvv%;;;', array(
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
            '%aliasName%'      => $aliasName,
        ));
    }

}
