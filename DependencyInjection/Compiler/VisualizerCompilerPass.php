<?php

/*
 * This file is part of the LinkinVisualizeEntityBundle package.
 *
 * (c) Viktor Linkin <adrenalinkin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Linkin\Bundle\VisualizeEntityBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class VisualizerCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('linkin_visualize_entity.handler.visualizer')) {
            return;
        }

        // list of the all automatically used ordered visualizers
        $visualizers = new \SplPriorityQueue();

        // list of the all manually only used visualizers
        $manualVisualizers = [];

        // list of the all visualizers which work with traversable types
        $traversableVisualizers = [];

        foreach ($container->findTaggedServiceIds('linkin_visualize_entity.visualizer') as $id => $attributes) {
            $attributes    = reset($attributes);
            $manualUsage   = isset($attributes['manual_usage_only']) ? $attributes['manual_usage_only'] : 0;
            $isTraversable = isset($attributes['traversable']) ? $attributes['traversable'] : 0;

            $serviceReference = new Reference($id);

            if ($isTraversable) {
                $traversableVisualizers[$id] = $serviceReference;
            }

            if ($manualUsage) {
                $manualVisualizers[$id] = $serviceReference;

                continue;
            }

            $manualVisualizers[$id] = $serviceReference;
            $priority = isset($attributes['priority']) ? $attributes['priority'] : 0;

            $visualizers->insert($serviceReference, $priority);
        }

        $visualizers = iterator_to_array($visualizers);

        // list fo the all visualizers which work with NOT traversable types
        $notTraversableVisualizers = [];

        foreach ($visualizers as $visualizer) {
            $id = (string) $visualizer;

            if (empty($traversableVisualizers[$id])) {
                $notTraversableVisualizers[$id] = $visualizer;
            }
        }

        $handlerDefinition = $container->getDefinition('linkin_visualize_entity.handler.visualizer');

        // all visualizers for traversable types will receive handler, which contains visualizers for NOT traversable
        // types to prevent circular references
        foreach ($traversableVisualizers as $id => $traversableVisualizer) {
            $handlerClone = clone $handlerDefinition;

            $handlerClone->replaceArgument(0, $notTraversableVisualizers);

            $container
                ->getDefinition($id)
                ->replaceArgument(0, $handlerClone)
            ;
        }

        $handlerDefinition
            ->replaceArgument(0, $visualizers)
            ->replaceArgument(1, $manualVisualizers)
        ;
    }
}
