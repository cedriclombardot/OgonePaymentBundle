<?php

namespace Cedriclombardot\OgonePaymentBundle\Tests\Builder;

use Cedriclombardot\OgonePaymentBundle\Tests\TestCase;
use Cedriclombardot\OgonePaymentBundle\Builder\TransactionBuilder;
use Cedriclombardot\OgonePaymentBundle\Builder\TransactionFormBuilder;
use Cedriclombardot\OgonePaymentBundle\Config\SecureConfigurationContainer;
use Cedriclombardot\OgonePaymentBundle\Config\ConfigurationContainer;
use Cedriclombardot\OgonePaymentBundle\Propel\OgoneAlias;
use Cedriclombardot\OgonePaymentBundle\Propel\OgoneOrder;
use Cedriclombardot\OgonePaymentBundle\Propel\OgoneAliasPeer;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\Extension\DependencyInjection\DependencyInjectionExtension;

class TransactionBuilderTest extends TestCase
{
    protected $builder;

    public function setUp()
    {
        $formFactory = new FormFactory(array(0 => new DependencyInjectionExtension($this->getContainer(), array('field' => 'form.type.field', 'form' => 'form.type.form','hidden' => 'form.type.hidden', ), array('form' => array(0 => 'form.type_extension.field', 1 => 'form.type_extension.csrf')), array(0 => 'form.type_guesser.validator'))));
        $secureConfigurationContainer = new SecureConfigurationContainer(array('shaInKey' => 'testHash', 'algorithm' => 'sha512'));
        $configurationContainer = new ConfigurationContainer(array());

        $formBuilder = new TransactionFormBuilder($formFactory, $secureConfigurationContainer);
        $this->builder = new TransactionBuilderMock($formBuilder, $configurationContainer);
    }

    public function testUseAlias()
    {
        $alias = new OgoneAlias();
        $alias->setUsage('USAGE');
        $alias->setOperation(OgoneAliasPeer::OPERATION_BYMERCHANT);
        $alias->setLabel('LABEL');

        $this->assertEquals(null, $this->builder->getConfigurationContainer()->all());

        $this->builder->useAlias($alias);
        $this->assertEquals(array(
            'aliasusage'     => 'LABEL',
            'aliasoperation' => 'BYMERCHANT',
        ), $this->builder->getConfigurationContainer()->all());
    }

    public function testConfigure()
    {
        $this->assertEquals(null, $this->builder->getConfigurationContainer()->all());

        $this->builder->configure()
                        ->setBgColor('red')
                      ->end();

        $this->assertEquals(array(
            'bgcolor'     => 'red',
        ), $this->builder->getConfigurationContainer()->all());
    }

    public function testPrepareTransaction()
    {
        $this->builder->order()
                        ->setAmount(150)
                      ->end();

        $this->assertEquals(null, $this->builder->getConfigurationContainer()->all());

        $this->builder->prepareTransaction();

        $this->assertEquals(array(
            'amount'     => 150,
        ), $this->builder->getConfigurationContainer()->all());
    }
}

class TransactionBuilderMock extends TransactionBuilder
{
    public function __construct(TransactionFormBuilder $transactionFormBuilder, ConfigurationContainer $configurationContainer)
    {
        parent::__construct($transactionFormBuilder, $configurationContainer);
        $this->order = new OgoneOrderMock();
    }

    public function __call($method, $args)
    {
        return call_user_func_array(array($this,$method), $args);
    }

    public function getConfigurationContainer()
    {
        return $this->configurationContainer;
    }
}

class OgoneOrderMock extends OgoneOrder
{
    public function save(\PropelPDO $con = null)
    {
        return $this;
    }
}
