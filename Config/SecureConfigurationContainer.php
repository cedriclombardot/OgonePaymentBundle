<?php

namespace Pilot\OgonePaymentBundle\Config;

class SecureConfigurationContainer extends ConfigurationContainer
{
    protected $properties = array(
       'ALGORITHM', 'SHAINKEY', 'SHAOUTKEY',
       // Batch call
       'USERID', 'USERPASSWORD'
    );

}
