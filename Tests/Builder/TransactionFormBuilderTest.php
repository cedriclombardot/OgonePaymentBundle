<?php 

namespace Cedriclombardot\OgonePaymentBundle\Tests\Builder;

use Cedriclombardot\OgonePaymentBundle\Tests\TestCase;
use Cedriclombardot\OgonePaymentBundle\Builder\TransactionFormBuilder;
use Cedriclombardot\OgonePaymentBundle\Config\SecureConfigurationContainer;
use Cedriclombardot\OgonePaymentBundle\Config\ConfigurationContainer;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\Extension\DependencyInjection\DependencyInjectionExtension;

class TransactionFormBuilderTest extends TestCase
{
	protected $builder;
	
	public function setUp()
	{
		$formFactory = new FormFactory(array(0 => new DependencyInjectionExtension($this->getContainer(), array('field' => 'form.type.field', 'form' => 'form.type.form','hidden' => 'form.type.hidden', ), array('form' => array(0 => 'form.type_extension.field', 1 => 'form.type_extension.csrf')), array(0 => 'form.type_guesser.validator'))));
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
		
		$hash = strtoupper(hash('sha512','TITLE=page demotestHash'));
		$this->assertEquals($hash, $this->builder->getSHASign($configurationContainer));
	}
	
	public function testBuild()
	{
		$configurationContainer = new ConfigurationContainer(array('title' => 'page demo'));
		$this->builder->build($configurationContainer);
		
		$this->assertEquals(2, $this->builder->getForm()->count()); // TITLE + SHASIGN
		$this->assertTrue($this->builder->getForm()->has('TITLE'));
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