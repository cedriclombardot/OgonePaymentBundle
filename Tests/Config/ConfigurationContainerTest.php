<?php

namespace Pilot\OgonePaymentBundle\Tests\Config;

use Pilot\OgonePaymentBundle\Tests\TestCase;
use Pilot\OgonePaymentBundle\Config\ConfigurationContainer;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class ConfigurationContainerTest extends TestCase
{
    public function testFindProperty()
    {
        $config = new ConfigurationContainer;

        $this->assertEquals('TITLE', $config->findProperty('TITLE'));
        $this->assertEquals('TITLE', $config->findProperty('title'));
        $this->assertEquals('TITLE', $config->findProperty('Title'));

        $this->assertFalse($config->findProperty('Unknown'));
    }

    public function testSetProperty()
    {
        $config = new ConfigurationContainer;

        $config->setTitle('test');
        $this->assertEquals('test', $config->get('TITLE'));
    }

    public function testGetProperty()
    {
        $config = new ConfigurationContainer;

        $config->setTitle('test');
        $this->assertEquals('test', $config->getTitle());
    }

    public function testDefaultProperties()
    {
        $config = new ConfigurationContainer(array('title' =>'demo'));

        $this->assertEquals('demo', $config->getTitle());
    }

    public function testSetTemplate()
    {
        $config = new ConfigurationContainer;
        $config->setRouter($this->getContainer()->get('router'), 'ogone_template');

        $config->setTemplate('::ogone.html.twig');
        $this->assertEquals('http://localhost/ogone/template/::ogone.html.twig', $config->getTP());
    }

}
