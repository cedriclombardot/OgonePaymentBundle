<?php

namespace Pilot\OgonePaymentBundle\File;

use Pilot\OgonePaymentBundle\Propel\OgoneOrderQuery;

class OgoneDownloadedPayment
{
    protected $xml;

    const PAY_STATUS_AUTHORIZED = 5;
    const PAY_STATUS_OK = 9;
    const PAY_STATUS_REFUSE = 2;

    public function __construct(\SimpleXmlElement $xml)
    {
        $this->xml = $xml;
    }

    public function getAttribute($name)
    {
        $attr = $this->xml->xpath('@'.$name);

        if (!isset($attr[0])) {
            return false;
        }

        return (string) $attr[0][$name];
    }

    public function isPayed()
    {
        return $this->getStatus() == self::PAY_STATUS_OK;
    }

    public function isError()
    {
        return $this->getStatus() == self::PAY_STATUS_REFUSE;
    }

    public function updateRelatedOgoneOrder()
    {
        $order = OgoneOrderQuery::create()->findPk($this->getRef());

        $order->setCurrency($this->getAttribute('CUR'));
        $order->setPaymentMethod($this->getAttribute('METHOD'));
        $order->setAcceptance($this->getAttribute('ACCEPT'));
        $order->setStatus($this->getAttribute('STATUS'));
        $order->setCardNumber($this->getAttribute('CARD'));
        $order->setClientName($this->getAttribute('FACNAME1'));
        $order->setTransactionDate(\DateTime::createFromFormat('d/m/y', $this->getAttribute('PAYDATE')));
        $order->setPayid($this->getAttribute('ID'));
        $order->setNcError($this->getAttribute('NCERROR'));
        $order->setBrand($this->getAttribute('BRAND'));
        $order->save();
    }

    /**
    * eg getStatus => getAttribute('STATUS')
    **/
    public function __call($method, $args)
    {
        $method = strtoupper(str_replace('get', '', $method));

        return $this->getAttribute($method);
    }
}
