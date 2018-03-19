<?php

/*
 * This file is part of the LinkinVisualizeEntityBundle package.
 *
 * (c) Viktor Linkin <adrenalinkin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Linkin\Bundle\VisualizeEntityBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('linkin_visualize_entity')
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('configName')
            ->prototype('array')
                ->info('List of the fields that is should be displayed on page')
                ->example([
                    'default' => [
                        'className' => 'AcmeBundle:User',
                        'label_translation' => [
                            'domain' => 'acme_messages',
                            'prefix' => 'acme.user.fields.',
                        ],
                        'fields' => [
                            'firstName' => null,
                            'birthday' => [
                                'label' => 'acme.',
                                'visualizer' => 'acme.visualizer.datetime',
                                'visualizer_options' => ['format' => 'Y-m-d'],
                            ],
                        ],
                    ],
                ])
                ->children()
                    ->scalarNode('className')
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
                    ->arrayNode('label_translation')
                        ->children()
                            ->scalarNode('domain')
                                ->defaultNull()
                            ->end()
                            ->scalarNode('prefix')
                                ->defaultNull()
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('fields')
                        ->useAttributeAsKey('fieldName')
                        ->prototype('array')
                            ->children()
                                ->scalarNode('getter')
                                    ->info('Getter for get field value')
                                    ->defaultNull()
                                ->end()
                                ->scalarNode('label')
                                    ->info('Label for the field')
                                    ->defaultNull()
                                ->end()
                                ->scalarNode('visualizer')
                                    ->info('Name of the visualizer for the field')
                                    ->defaultNull()
                                ->end()
                                ->arrayNode('visualizer_options')
                                    ->info('Name of the visualizer for the field')
                                    ->useAttributeAsKey('optionName')
                                    ->prototype('variable')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
