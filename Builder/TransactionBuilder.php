<?php

namespace Cedriclombardot\OgonePaymentBundle\Builder;

use Cedriclombardot\OgonePaymentBundle\Config\ConfigurationContainer;

use Cedriclombardot\OgonePaymentBundle\Propel\OgoneOrder;

class TransactionBuilder
{
    protected $transactionFormBuilder;

    protected $configurationContainer;

    protected $order;

    public function __construct(TransactionFormBuilder $transactionFormBuilder, ConfigurationContainer $configurationContainer)
    {
        $this->transactionFormBuilder = $transactionFormBuilder;
        $this->configurationContainer = $configurationContainer;
        $this->order = new OgoneOrder();
    }

    public function configure()
    {
        return $this->configurationContainer->onEnd($this);
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

}
