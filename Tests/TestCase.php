<?php

namespace Pilot\OgonePaymentBundle\Tests;


class TestCase extends \PHPUnit_Framework_TestCase
{
    static $kernel;

    static $isBuilt = false;

    public function getContainer()
    {
        return $this->getBootedKernel()->getContainer();
    }

    protected function getBootedKernel()
    {
        self::$kernel = new MyKernel('test', true);
        self::$kernel->boot();

        return self::$kernel;
    }

}


class MyKernel extends \Symfony\Component\HttpKernel\Kernel
{
    public function registerBundles()
    {
        return array(
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),

            // Propel
            new \Propel\PropelBundle\PropelBundle(),

            // Ogone
            new \Pilot\OgonePaymentBundle\PilotOgonePaymentBundle(),

        );
    }

    public function registerContainerConfiguration(\Symfony\Component\Config\Loader\LoaderInterface $loader)
    {
       $loader->load(__DIR__.'/config_'.$this->getEnvironment().'.yml');
    }
}
