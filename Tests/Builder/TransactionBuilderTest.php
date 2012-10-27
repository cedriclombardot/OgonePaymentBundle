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
use Cedriclombardot\OgonePaymentBundle\Batch\TransactionManager;

class TransactionBuilderTest extends TestCase
{
    protected $builder;

    public function setUp()
    {
        $formFactory = new FormFactory($this->getContainer()->get('form.registry'), $this->getContainer()->get('form.resolved_type_factory'));
        $secureConfigurationContainer = new SecureConfigurationContainer(array('shaInKey' => 'testHash', 'algorithm' => 'sha512'));
        $configurationContainer = new ConfigurationContainer(array());
        $tm = new TransactionManager($configurationContainer, $this->getContainer()->get('ogone.batch_request'));

        $formBuilder = new TransactionFormBuilder($formFactory, $secureConfigurationContainer);
        $this->builder = new TransactionBuilderMock($formBuilder, $configurationContainer, $tm);
    }

    public function testUseAlias()
    {
        $alias = new OgoneAlias();
        $alias->setName('USAGE');
        $alias->setOperation(OgoneAliasPeer::OPERATION_BYMERCHANT);

        $this->assertEquals(null, $this->builder->getConfigurationContainer()->all());

        $this->builder->useAlias($alias);
        $this->assertEquals(array(
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
    public function __construct(TransactionFormBuilder $transactionFormBuilder, ConfigurationContainer $configurationContainer, TransactionManager $transactionManager)
    {
        parent::__construct($transactionFormBuilder, $configurationContainer, $transactionManager);
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
