<?php

namespace Cedriclombardot\OgonePaymentBundle\Manager;

use Cedriclombardot\OgonePaymentBundle\Config\ConfigurationContainer;
use Cedriclombardot\OgonePaymentBundle\Config\SecureConfigurationContainer;

class Api
{
    protected $configurationContainer;

    protected $secureConfigurationContainer;

    protected $request;

    public function __construct(ConfigurationContainer $configurationContainer, SecureConfigurationContainer $secureConfigurationContainer)
    {
        $this->configurationContainer = $configurationContainer;
        $this->secureConfigurationContainer = $secureConfigurationContainer;
    }

    public function getPspid()
    {
        return $this->configurationContainer->getPspid();
    }

    public function getUserPassword()
    {
       return $this->secureConfigurationContainer->getUserPassword();
    }

    public function getUserId()
    {
       return $this->secureConfigurationContainer->getUserId();
    }

    public function createRequest()
    {
        return $this->request = new ApiRequest($this);
    }

}
