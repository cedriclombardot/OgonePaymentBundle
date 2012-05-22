<?php

namespace Cedriclombardot\OgonePaymentBundle\Config;

class SecureConfigurationContainer extends ConfigurationContainer
{
    protected $properties = array(
       'ALGORITHM', 'SHAINKEY', 'SHAOUTKEY'
    );

}
