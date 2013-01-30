<?php

namespace Cedriclombardot\OgonePaymentBundle\Tests\Feedback;

use Cedriclombardot\OgonePaymentBundle\Tests\TestCase;
use Cedriclombardot\OgonePaymentBundle\Feedback\TransactionFeedbacker;
use Cedriclombardot\OgonePaymentBundle\Config\SecureConfigurationContainer;
use Symfony\Component\HttpFoundation\Request;

class TransactionFeedbackerTest extends TestCase
{
    public function testIsValidCall()
    {
        $request = new Request();
        $secureConfigurationContainer = new SecureConfigurationContainer(array('shaOutKey' => 'testHash', 'algorithm' => 'sha512'));

        $feedbacker = new TransactionFeedbacker($request, $secureConfigurationContainer);

        $this->assertFalse($feedbacker->isValidCall(), 'SHASIGN is mandatory');

        $request->request->set('SHASIGN', 'INVALID');
        $request->request->set('orderId', 12);

        $this->assertFalse($feedbacker->isValidCall(), 'SHASIGN should be valid');

        $request->request->set('SHASIGN', strtoupper(hash('sha512','ORDERID=12testHash')));

        $this->assertTrue($feedbacker->isValidCall());
    }
}
