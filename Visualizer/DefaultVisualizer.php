<?php

/*
 * This file is part of the LinkinVisualizeEntityBundle package.
 *
 * (c) Viktor Linkin <adrenalinkin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Linkin\Bundle\VisualizeEntityBundle\Visualizer;

/**
 * Provides default visualization
 *
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class DefaultVisualizer implements VisualizerInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports($type, $value = null, array $context = [])
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function visualize($value, $type = null, array $options = [], array $context = [])
    {
        if (is_array($value)) {
            return sprintf('Array with %s items', count($value));
        }

        if (is_resource($value)) {
            return 'Resource';
        }

        if (is_scalar($value) || is_object($value) && method_exists($value, '__toString')) {
            return (string) $value;
        }

        return gettype($value);
    }
}
