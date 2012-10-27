<?php

namespace Cedriclombardot\OgonePaymentBundle\Builder;

use Cedriclombardot\OgonePaymentBundle\Config\ConfigurationContainer;

use Cedriclombardot\OgonePaymentBundle\Propel\OgoneOrder;
use Cedriclombardot\OgonePaymentBundle\Propel\OgoneAlias;
use Cedriclombardot\OgonePaymentBundle\Batch\TransactionManager;

class TransactionBuilder
{
    protected $transactionFormBuilder;

    protected $configurationContainer;

    protected $batchTransactionManager;

    protected $order;

    public function __construct(TransactionFormBuilder $transactionFormBuilder, ConfigurationContainer $configurationContainer, TransactionManager $batchTransactionManager)
    {
        $this->transactionFormBuilder  = $transactionFormBuilder;
        $this->configurationContainer  = $configurationContainer;
        $this->batchTransactionManager = $batchTransactionManager;
        $this->order = new OgoneOrder();
    }

    public function configure()
    {
        return $this->configurationContainer->onEnd($this);
    }

    public function resetOrder()
    {
        $this->order = new OgoneOrder();

        return $this;
    }

    public function order()
    {
        return $this->order->onEnd($this);
    }

    public function getForm()
    {
        return $this->prepareTransaction()
                    ->transactionFormBuilder
                    ->build($this->configurationContainer)
                    ->getForm();
    }

    protected function prepareTransaction()
    {
        foreach ($this->order->toOgone() as $key => $value) {
            $this->configurationContainer->{'set'.$key}($value);
        }

        return $this;
    }

    public function useAlias(OgoneAlias $alias)
    {
        foreach ($alias->toOgone() as $key => $value) {
            $this->configurationContainer->{'set'.$key}($value);
        }

        return $this;
    }

    public function getBatchTransactionManagerCsvRow()
    {
        return $this->batchTransactionManager
                    ->getSaleCsvRow(
                        $this->order->getAmount(),
                        null,
                        null,
                        null,
                        $this->order->getOgoneClient()->getFullname(),
                        null,
                        $this->order->getId(),
                        $this->configurationContainer->getAlias()
                );
    }

}
