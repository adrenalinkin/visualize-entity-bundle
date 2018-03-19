<?php

/*
 * This file is part of the LinkinVisualizeEntityBundle package.
 *
 * (c) Viktor Linkin <adrenalinkin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Linkin\Bundle\VisualizeEntityBundle\Exception;

/**
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class VisualizerNotFoundException extends \RuntimeException
{
    /**
     * @param string $type
     */
    public function __construct($type)
    {
        $this->message = sprintf('Visualizer for type "%s" was not found', $type);
    }
}
