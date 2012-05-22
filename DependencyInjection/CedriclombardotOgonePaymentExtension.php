<?php


namespace Cedriclombardot\OgonePaymentBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;


class CedriclombardotOgonePaymentExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        $container->setParameter('ogone.configuration.defaults', array_merge($config['general'], $config['design']));
        $container->setParameter('ogone.configuration.secure', $config['secret']);
    }

    public function getAlias()
    {
        return 'cedriclombardot_ogone_payment';
    }
}
