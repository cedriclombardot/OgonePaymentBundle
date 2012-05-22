<?php

namespace Cedriclombardot\OgonePaymentBundle\Propel;

use Cedriclombardot\OgonePaymentBundle\Propel\om\BaseOgoneOrder;

use Cedriclombardot\OgonePaymentBundle\Propel\OgoneClient;

/**
 * Skeleton subclass for representing a row from the 'ogone_order' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.src.Cedriclombardot.OgonePaymentBundle.Propel
 */
class OgoneOrder extends BaseOgoneOrder {

    protected $onEnd;

    public function setClient(OgoneClient $client)
    {
        return $this->setOgoneClient($client);
    }

    public function onEnd($object)
    {
        $this->onEnd = $object;

        return $this;
    }

    public function end()
    {
        $this->save();

        $toReturn = $this->onEnd;
        $this->onEnd = null;

        return $toReturn;
    }

    public function toOgone()
    {
        $convertion = array(
           'orderId'    => 'Id',
           'amount'     => 'Amount',
           'amountHtva' => 'AmountHtva',
           'UserId'     => 'ClientId',
           'Com'        => 'Description',
           'Operation'  => 'Operation',
           'PM'         => 'PaymentMethod',
           'ECOM_SHIPTO_TELECOM_PHONE_NUMBER' => 'ShiptoPhoneNumber',
           'ECOM_SHIPTO_ONLINE_EMAIL'       => 'ShiptoEmail',
           'ECOM_SHIPTO_POSTAL_NAME_PREFIX' => 'ShiptoCivility',
           'ECOM_SHIPTO_POSTAL_NAME_FIRST'  => 'ShiptoFirstname',
           'ECOM_SHIPTO_POSTAL_NAME_LAST'   => 'ShiptoName',
           'ECOM_SHIPTO_COMPANY'            => 'ShiptoCompagny',
           'ECOM_SHIPTO_POSTAL_STREET_LINE1'=> 'ShiptoAddress',
           'ECOM_SHIPTO_POSTAL_STREET_LINE2'=> 'ShiptoAddress2',
           'ECOM_SHIPTO_POSTAL_CITY'        => 'ShiptoCity',
           'ECOM_SHIPTO_POSTAL_COUNTRYCODE' => 'ShiptoCountrycode',
           'ECOM_SHIPTO_POSTAL_POSTALCODE'  => 'ShiptoPostalcode',
           'ECOM_BILLTO_POSTAL_STREET_LINE1'=> 'BilltoAddress',
           'ECOM_BILLTO_POSTAL_STREET_LINE2'=> 'BilltoAddress2',
           'ECOM_BILLTO_POSTAL_CITY'        => 'BilltoCity',
           'ECOM_BILLTO_POSTAL_COUNTRYCODE' => 'BilltoCountrycode',
           'ECOM_BILLTO_POSTAL_POSTALCODE'  => 'BilltoPostalcode',
        );

        foreach ($convertion as $ogoneKey => $propelGetter) {
            $convertion[$ogoneKey] = $this->{'get'.$propelGetter}();
        }

        if ($this->getOgoneClient()) {
               $convertion = array_merge($convertion, $this->getOgoneClient()->toOgone());
        }

        return $convertion;
    }
} // OgoneOrder
