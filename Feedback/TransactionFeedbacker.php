<?php

namespace Pilot\OgonePaymentBundle\Feedback;

use Symfony\Component\HttpFoundation\Request;
use Pilot\OgonePaymentBundle\Config\ConfigurationContainer;
use Pilot\OgonePaymentBundle\Propel\OgoneOrderQuery;
use Pilot\OgonePaymentBundle\Propel\OgoneAliasQuery;
use Pilot\OgonePaymentBundle\Propel\OgoneAliasPeer;

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
        if (!$this->request->request->has('SHASIGN') && !$this->request->query->has('SHASIGN')) {
            return false;
        }
        // Check sign
        $toSign = array();
        if ($this->request->query->has('SHASIGN')) {
            foreach ($this->request->query->all() as $key => $val) {
                if($val != '') {
                    $toSign[strtoupper($key)] = $val;
                }
            }
        } else {
            foreach ($this->request->request->all() as $key => $val) {
                if($val != '') {
                    $toSign[strtoupper($key)] = $val;
                }
            }
        }

        unset($toSign["SHASIGN"]);
        ksort($toSign);

        $toHash = '';
        foreach ($toSign as $key => $val) {
            $toHash .= $key.'='.$val.$this->secureConfigurationContainer->getShaOutKey();
        }

        return $this->request->get('SHASIGN') === strtoupper(hash($this->secureConfigurationContainer->getAlgorithm(), $toHash));
    }

    public function hasOrder()
    {
        return $this->request->get('orderID') != '';
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

    public function hasAlias()
    {
        return $this->request->get('ALIAS') != '';
    }

    public function updateAlias()
    {
        $alias = OgoneAliasQuery::create()->findOneByUuid($this->request->get('ALIAS'));

        if (!$alias) {
            throw new \LogicException('Alias cant be invalid here !!');
        }

        if (OgoneCodes::isPayed($this->request->get('STATUS'))) {
            $alias->setStatus(OgoneAliasPeer::STATUS_ACTIVE);
        } elseif (OgoneCodes::isRefused($this->request->get('STATUS'))) {
            $alias->setStatus(OgoneAliasPeer::STATUS_ERROR);
        }

        // Update client info if user change is name for the cb
        if ($this->request->get('CN')) {
            $alias->getOgoneClient()->setFullName($this->request->get('CN'));
            $alias->getOgoneClient()->save();
        }

        $alias->save();

        return $this;
    }
}
