<?php

namespace Cedriclombardot\OgonePaymentBundle\Feedback;

use Symfony\Component\HttpFoundation\Request;
use Cedriclombardot\OgonePaymentBundle\Config\ConfigurationContainer;
use Cedriclombardot\OgonePaymentBundle\Propel\OgoneOrderQuery;

class TransactionFeedbacker
{
    protected $secureConfigurationContainer;

    public function __construct(Request $request, ConfigurationContainer $secureConfigurationContainer)
    {
        $this->request = $request;
        $this->secureConfigurationContainer = $secureConfigurationContainer;
    }

    public function isValidCall()
    {
        if (!$this->request->query->has('SHASIGN')) {
            return false;
        }

        // Check sign
        $toSign = array();
        foreach ($this->request->query->all() as $key => $val) {
            $toSign[strtoupper($key)] = $val;
        }

        unset($toSign["SHASIGN"]);
        ksort($toSign);

        $toHash = '';
        foreach ($toSign as $key => $val) {
            $toHash .= $key.'='.$val.$this->secureConfigurationContainer->getShaOutKey();
        }

        return $this->request->get('SHASIGN') === strtoupper(hash($this->secureConfigurationContainer->getAlgorithm(), $toHash));
    }

    public function updateOrder()
    {
        $order = OgoneOrderQuery::create()->findPk($this->request->get('orderID'));

        if (!$order) {
            throw new \LogicException('Order cant be invalid here !!');
        }

        $order->setCurrency($this->request->get('currency'));
        $order->setPaymentMethod($this->request->get('PM'));
        $order->setAcceptance($this->request->get('ACCEPTANCE'));
        $order->setStatus($this->request->get('STATUS'));
        $order->setCardNumber($this->request->get('CARDNO'));
        $order->setEd($this->request->get('ED'));
        $order->setClientName($this->request->get('CN'));
        $order->setTransactionDate(\DateTime::createFromFormat('m/d/y', $this->request->get('TRXDATE')));
        $order->setPayid($this->request->get('PAYID'));
        $order->setNcError($this->request->get('NCERROR'));
        $order->setBrand($this->request->get('BRAND'));
        $order->setIp($this->request->get('IP'));
        $order->save();

        return $this;
    }
}
