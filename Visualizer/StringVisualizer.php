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
 * Provides visualization for the string values
 *
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class StringVisualizer implements VisualizerInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports($type, $value = null, array $context = [])
    {
        return is_string($value) || in_array($type, ['string', 'text', 'guid']);
    }

    /**
     * {@inheritDoc}
     */
    public function visualize($value, $type = null, array $options = [], array $context = [])
    {
        return (string) $value;
    }
}
