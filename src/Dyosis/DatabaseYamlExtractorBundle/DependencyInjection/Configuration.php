<?php

namespace Dyosis\DatabaseYamlExtractorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package Dyosis\DatabaseYamlExtractorBundle\DependencyInjection
 * @author  Jean MARIUS <jean.marius@dyosis.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('database_yaml_extractor');
        $rootNode
	        ->children()
	            ->scalarNode('output_directory')
	            ->defaultValue('%kernel.root_dir%\\..\\database_extraction\\')
	        ;

        return $treeBuilder;
    }
}
