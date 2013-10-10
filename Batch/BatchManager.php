<?php

namespace Pilot\OgonePaymentBundle\Batch;

use Pilot\OgonePaymentBundle\Config\ConfigurationContainer;

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
