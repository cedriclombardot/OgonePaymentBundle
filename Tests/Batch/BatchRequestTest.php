<?php

namespace Cedriclombardot\OgonePaymentBundle\Tests\Batch;

use Cedriclombardot\OgonePaymentBundle\Tests\TestCase;
use Cedriclombardot\OgonePaymentBundle\Batch\BatchRequest;

class BatchRequestTest extends TestCase
{
    public function testGetUrl()
    {
        $batchRequest = new BatchRequestMock('test', $this->getContainer()->get('ogone.configuration'), $this->getContainer()->get('ogone.secure_configuration'));

        $this->assertEquals('https://secure.ogone.com/ncol/test/AFU_agree.asp', $batchRequest->getUrl());
    }
}

class BatchRequestMock extends BatchRequest
{
    public function __call($method, $args)
    {
        return call_user_func_array(array($this,$method), $args);
    }
}