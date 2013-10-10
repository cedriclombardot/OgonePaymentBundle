<?php

namespace Pilot\OgonePaymentBundle\Tests\Batch;

use Pilot\OgonePaymentBundle\Tests\TestCase;
use Pilot\OgonePaymentBundle\Config\ConfigurationContainer;
use Pilot\OgonePaymentBundle\Batch\AliasManager;
use Pilot\OgonePaymentBundle\Batch\BatchRequest;

class AliasManagerTest extends TestCase
{
    public function testBuildAliasCSVRow()
    {
        $aliasManager = new AliasManagerMock(new ConfigurationContainer(array('PSPID' => 'MyShop' )), new BatchRequest('test', $this->getContainer()->get('ogone.configuration'), $this->getContainer()->get('ogone.secure_configuration')));

        $csv = $aliasManager->buildAliasCSVRow(
            AliasManagerMock::OPERATION_ADD,
            'Customer123',
            'John Doe',
            '4111111111111111',
            '1012',
            'VISA');

        $this->assertEquals('ADDALIAS;Customer123;John Doe;4111111111111111;1012;VISA;MyShop;', $csv);
    }
}

class AliasManagerMock extends AliasManager
{
    public function __call($method, $args)
    {
        return call_user_func_array(array($this,$method), $args);
    }
}
