<?php

namespace Pilot\OgonePaymentBundle\Tests\Builder;

use Pilot\OgonePaymentBundle\Tests\TestCase;
use Pilot\OgonePaymentBundle\Builder\TransactionFormBuilder;
use Pilot\OgonePaymentBundle\Config\SecureConfigurationContainer;
use Pilot\OgonePaymentBundle\Config\ConfigurationContainer;
use Symfony\Component\Form\FormFactory;

class TransactionFormBuilderTest extends TestCase
{
    protected $builder;

    public function setUp()
    {
        $formFactory = new FormFactory($this->getContainer()->get('form.registry'), $this->getContainer()->get('form.resolved_type_factory'));
        $secureConfigurationContainer = new SecureConfigurationContainer(array('shaInKey' => 'testHash', 'algorithm' => 'sha512'));

        $this->builder = new TransactionFormBuilderMock($formFactory, $secureConfigurationContainer);
    }

    public function testGetForm()
    {
        $this->assertInstanceOf('Symfony\Component\Form\Form', $this->builder->getForm());
    }

    public function testGetSHASign()
    {
        $configurationContainer = new ConfigurationContainer(array('title' => 'page demo'));

        $hash = strtoupper(hash('sha512','AMOUNT=0testHashTITLE=page demotestHash'));
        $this->assertEquals($hash, $this->builder->getSHASign($configurationContainer));
    }

    public function testBuild()
    {
        $configurationContainer = new ConfigurationContainer(array('title' => 'page demo'));
        $this->builder->build($configurationContainer);

        $this->assertEquals(3, $this->builder->getForm()->count()); // TITLE + SHASIGN
        $this->assertTrue($this->builder->getForm()->has('TITLE'));
        $this->assertTrue($this->builder->getForm()->has('AMOUNT'));
        $this->assertTrue($this->builder->getForm()->has('SHASign'));
        $this->assertEquals('', $this->builder->getForm()->getName());
    }

}

class TransactionFormBuilderMock extends TransactionFormBuilder
{
    public function __call($method, $args)
    {
        return call_user_func_array(array($this,$method), $args);
    }
}
