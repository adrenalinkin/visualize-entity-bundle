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
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
interface VisualizerInterface
{
    /**
     * Visualize received data
     *
     * @param mixed       $value   Value for visualization
     * @param string|null $type    Type of the value for visualization. Supports PHP types and Doctrine types
     * @param array       $options Specific visualizer options
     * @param array       $context Specific data
     *
     * @return string
     */
    public function visualize($value, $type = null, array $options = [], array $context = []);

    /**
     * Check is this visualizer supports received data
     *
     * @param string $type    Type of the value for visualization. Supports PHP types and Doctrine types
     * @param mixed  $value   Value for visualization
     * @param array  $context Specific data
     *
     * @return boolean
     */
    public function supports($type, $value = null, array $context = []);
}
