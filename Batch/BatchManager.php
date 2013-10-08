<?php

namespace Cedriclombardot\OgonePaymentBundle\Batch;

use Cedriclombardot\OgonePaymentBundle\Config\ConfigurationContainer;

abstract class  BatchManager
{
    protected $configurationContainer;
    protected $batchRequest;

    public function __construct(ConfigurationContainer $configurationContainer, BatchRequest $request)
    {
        $this->configurationContainer = $configurationContainer;
        $this->batchRequest = $request;
    }
}
