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
 * Provides visualization for the integer values
 *
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class IntegerVisualizer implements VisualizerInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports($type, $value = null, array $context = [])
    {
        return is_int($value) || in_array($type, ['int', 'integer', 'smallint', 'bigint']);
    }

    /**
     * {@inheritDoc}
     */
    public function visualize($value, $type = null, array $options = [], array $context = [])
    {
        return (int) $value;
    }
}
