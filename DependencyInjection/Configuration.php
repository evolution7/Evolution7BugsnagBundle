<?php
/*
 * This file is part of the Evolution7BugsnagBundle.
 *
 * (c) Evolution 7 <http://www.evolution7.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Evolution7\BugsnagBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bugsnag');

        $rootNode
            ->children()
                ->scalarNode('api_key')->end()
                ->scalarNode('app_version')->end()
                ->arrayNode('notify_stages')
                    ->prototype('scalar')->end()
                ->end()
                ->booleanNode('report_in_dev')
                    ->defaultValue(false)
                ->end()
                ->arrayNode('release_stage')
                    ->children()
                        ->scalarNode('class')->end()
                    ->end()
                ->end()
                ->arrayNode('proxy')
                    ->children()
                        ->scalarNode('host')->end()
                        ->scalarNode('port')->end()
                        ->scalarNode('user')->end()
                        ->scalarNode('password')->end()
                    ->end()
                ->end()
            ->end();


        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
