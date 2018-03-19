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

use Linkin\Bundle\VisualizeEntityBundle\Handler\VisualizerHandler;

/**
 * Provides visualization for the array values
 *
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class ArrayVisualizer implements VisualizerInterface
{
    /**
     * Offset name set delimiter option
     */
    const OPTION_DELIMITER = 'delimiter';

    /**
     * Instance of the visualizer handler
     *
     * @var VisualizerHandler
     */
    private $handler;

    /**
     * @param VisualizerHandler $handler
     */
    public function __construct(VisualizerHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * {@inheritDoc}
     */
    public function supports($type, $value = null, array $context = [])
    {
        $allowedTypes = ['array', 'simple_array', 'json', 'json_array'];

        return is_array($value) || in_array($type, $allowedTypes);
    }

    /**
     * {@inheritDoc}
     */
    public function visualize($value, $type = null, array $options = [], array $context = [])
    {
        $delimiter = array_key_exists(self::OPTION_DELIMITER, $options) ? $options[self::OPTION_DELIMITER] : ', ';
        $items     = [];

        foreach ($value as $item) {
            if ($this->supports($item, null, $context)) {
                $items[] = $this->visualize($item, null, $options, $context);
            } else {
                $items[] = $this->handler->visualize($item, null, null, $options, $context);
            }
        }

        return implode($delimiter, $items);
    }
}
