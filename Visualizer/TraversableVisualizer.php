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
 * Provides visualization for the Traversable objects
 *
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class TraversableVisualizer extends ArrayVisualizer
{
    /**
     * {@inheritDoc}
     */
    public function supports($type, $value = null, array $context = [])
    {
        return $value instanceof \Traversable;
    }
}
