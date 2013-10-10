<?php

namespace Pilot\OgonePaymentBundle\Tests\Batch;

use Pilot\OgonePaymentBundle\Tests\TestCase;
use Pilot\OgonePaymentBundle\Config\ConfigurationContainer;
use Pilot\OgonePaymentBundle\Batch\TransactionManager;
use Pilot\OgonePaymentBundle\Batch\BatchRequest;

class TransactionManagerTest extends TestCase
{
    public function testBuildAliasCSVRow()
    {
        $transactionManager = new TransactionManagerMock(new ConfigurationContainer(array('PSPID' => 'MyShop', 'CURRENCY' => 'EUR' )), new BatchRequest('test', $this->getContainer()->get('ogone.configuration'), $this->getContainer()->get('ogone.secure_configuration')));

        $csv = $transactionManager->buildTransactionCSVRow(
            TransactionManagerMock::OPERATION_RES,
            10,
            'VISA',
            '4111111111111111',
            '1215',
            'John Doe',
            '123',
            '12',
            'aliasName'
        );

        $this->assertEquals('10;EUR;VISA;4111111111111111;1215;12;;John Doe;;RES;;;;MyShop;;;aliasName;;;;123;;;', $csv);
    }
}

class TransactionManagerMock extends TransactionManager
{
    public function __call($method, $args)
    {
        return call_user_func_array(array($this,$method), $args);
    }
}
