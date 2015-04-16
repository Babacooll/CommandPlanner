<?php
namespace CommandPlanner\Config;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Class Configuration
 *
 * @package CommandPlanner\Config
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('command_planner');

        $rootNode
            ->children()
                ->scalarNode('application')->isRequired()->end()
                ->arrayNode('commands')->isRequired()->requiresAtLeastOneElement()->useAttributeAsKey('name')
                    ->prototype('array')
                    ->children()
                        ->scalarNode('namespace')->isRequired()->end()
                        ->scalarNode('timing')->isRequired()->end()
                        ->scalarNode('log_file')->isRequired()->end()
                        ->arrayNode('parameters')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('options')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
