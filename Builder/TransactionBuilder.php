<?php

namespace Cedriclombardot\OgonePaymentBundle\Builder;

use Doctrine\Common\Persistence\ObjectManager;
use Cedriclombardot\OgonePaymentBundle\Config\ConfigurationContainer;
use Cedriclombardot\OgonePaymentBundle\Entity\OgoneOrder;
use Cedriclombardot\OgonePaymentBundle\Entity\OgoneAlias;
use Cedriclombardot\OgonePaymentBundle\Batch\TransactionManager;

class TransactionBuilder
{
    protected $transactionFormBuilder;

    protected $configurationContainer;

    protected $batchTransactionManager;

    protected $om;

    protected $order;

    public function __construct(TransactionFormBuilder $transactionFormBuilder, ConfigurationContainer $configurationContainer, TransactionManager $batchTransactionManager, ObjectManager $om)
    {
        $this->transactionFormBuilder  = $transactionFormBuilder;
        $this->configurationContainer  = $configurationContainer;
        $this->batchTransactionManager = $batchTransactionManager;
        $this->om = $om;
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
        return $this
            ->prepareTransaction()
            ->transactionFormBuilder
            ->build($this->configurationContainer)
            ->getForm()
        ;
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
                $this->order->getClient()->getFullname(),
                null,
                $this->order->getId(),
                $this->configurationContainer->getAlias()
            )
        ;
    }

    public function save()
    {
        $this->om->persist($this->order);
        $this->om->flush();

        return $this;
    }
}
