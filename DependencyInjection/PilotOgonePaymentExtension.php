<?php

namespace Pilot\OgonePaymentBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;

class PilotOgonePaymentExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        $container->setParameter('ogone.use_aliases', $config['general']['use_aliases']);
        unset($config['general']['use_aliases']);

        $container->setParameter('ogone.env', $config['general']['env']);
        unset($config['general']['env']);

        if (count($config['general']['brands']) == 0) {
            $config['general']['brands'] = array('VISA', 'MasterCard');
        }
        $container->setParameter('ogone.brands', $config['general']['brands']);
        unset($config['general']['brands']);

        $container->setParameter('ogone.configuration.defaults', array_merge($config['general'], $config['design']));
        $container->setParameter('ogone.configuration.secure', $config['secret']);
    }

    public function getAlias()
    {
        return 'pilot_ogone_payment';
    }
}
