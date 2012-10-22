<?php

namespace Cedriclombardot\OgonePaymentBundle\Batch;

use Cedriclombardot\OgonePaymentBundle\Config\ConfigurationContainer;

class AliasManager
{
    const OPERATION_ADD =  'ADDALIAS';
    const OPERATION_DEL =  'DELALIAS';

    protected $configurationContainer;
    protected $batchRequest;

    public function __construct(ConfigurationContainer $configurationContainer, BatchRequest $request)
    {
        $this->configurationContainer = $configurationContainer;
        $this->batchRequest = $request;
    }

    public function checkAddAlias($aliasName, $customerName, $cardNumber, $expirationDate, $brand)
    {
        return $this->batchRequest->check(
            $this->buildAliasCSVRow(self::OPERATION_ADD, $aliasName, $customerName, $cardNumber, $expirationDate, $brand),
            'CHECKALIAS'.time()
        );
    }

    public function addAlias($aliasName, $customerName, $cardNumber, $expirationDate, $brand)
    {
        return $this->batchRequest->process(
            $this->buildAliasCSVRow(self::OPERATION_ADD, $aliasName, $customerName, $cardNumber, $expirationDate, $brand),
            'ADDALIAS'.time()
        );
    }

    public function deleteAlias($aliasName, $customerName, $cardNumber, $expirationDate, $brand)
    {
        return $this->batchRequest->process(
            $this->buildAliasCSVRow(self::OPERATION_DEL, $aliasName, $customerName, $cardNumber, $expirationDate, $brand),
            'DELALIAS'.time()
        );
    }

    protected function buildAliasCSVRow($operation, $aliasName, $customerName, $cardNumber, $expirationDate, $brand)
    {
        return strtr('%operation%;%aliasName%;%customerName%;%cardNumber%;%expirationDate%;%brand%;%pspid%;', array(
            '%operation%'      => $operation,
            '%aliasName%'      => $aliasName,
            '%customerName%'   => $customerName,
            '%cardNumber%'     => $cardNumber,
            '%expirationDate%' => $expirationDate,
            '%brand%'          => $brand,
            '%pspid%'          => $this->configurationContainer->get('PSPID'),
        ));
    }

}