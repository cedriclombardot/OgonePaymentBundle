<?php

namespace Cedriclombardot\OgonePaymentBundle\Propel;

use Cedriclombardot\OgonePaymentBundle\Propel\om\BaseOgoneClient;


/**
 * Skeleton subclass for representing a row from the 'ogone_client' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.src.Cedriclombardot.OgonePaymentBundle.Propel
 */
class OgoneClient extends BaseOgoneClient {

    public function toOgone()
    {
        $convertion = array(
           'UserId'      => 'Id',
           'GENDER'        => 'Gender',
           'CIVILITY'      => 'Civility',
           'CN'            => 'Fullname',
           'ECOM_BILLTO_POSTAL_NAME_FIRST'  => 'Firstname',
           'ECOM_BILLTO_POSTAL_NAME_LAST'   => 'Name',
           'EMAIL'         => 'Email',
           'OWNERZIP'      => 'ZipCode',
           'OWNERADDRESS'  => 'Address',
           'OWNERADDRESS2' => 'Address2',
           'OWNERCTY'      => 'City',
           'OWNERTOWN'     => 'Town',
           'OWNERTELNO'    => 'PhoneNumber',
           'OWNERTELNO2'   => 'PhoneNumber2',
           'ECOM_SHIPTO_DOB' => 'Birthdate',
        );

        foreach ($convertion as $ogoneKey => $propelGetter) {
            $convertion[$ogoneKey] = $this->{'get'.$propelGetter}();
        }

        return $convertion;
    }
} // OgoneClient
