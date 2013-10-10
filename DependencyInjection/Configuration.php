<?php

namespace Pilot\OgonePaymentBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class contains the configuration information for the bundle
 *
 * @author clombardot
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('ogone');

        $rootNode
            ->children()
                ->arrayNode('secret')
                    ->children()
                        ->scalarNode('shaInKey')->end()
                        ->scalarNode('shaOutKey')->end()
                        ->scalarNode('algorithm')->defaultValue('sha1')->end()
                        ->scalarNode('userId')->end()
                        ->scalarNode('userPassword')->end()
                    ->end()
                ->end()
                ->arrayNode('general')
                    ->children()
                        ->scalarNode('PSPID')->end()
                        ->scalarNode('currency')->end()
                        ->scalarNode('language')->end()
                        ->booleanNode('use_aliases')->defaultFalse()->end()
                        ->scalarNode('env')->defaultValue('test')->end()
                        ->arrayNode('brands')
                            ->useAttributeAsKey('key')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('design')
                    ->children()
                        ->scalarNode('title')->end()
                        ->scalarNode('bgColor')->end()
                        ->scalarNode('txtColor')->end()
                        ->scalarNode('tblBgColor')->end()
                        ->scalarNode('buttonBgColor')->end()
                        ->scalarNode('buttonTxtColor')->end()
                        ->scalarNode('fontType')->end()
                        ->scalarNode('template')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
