<?php

/*
 * This file is part of the LinkinVisualizeEntityBundle package.
 *
 * (c) Viktor Linkin <adrenalinkin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Linkin\Bundle\VisualizeEntityBundle\Handler;

use Linkin\Bundle\VisualizeEntityBundle\Exception\VisualizerNotFoundException;

/**
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class VisualizerHandler
{
    /**
     * @var \Linkin\Bundle\VisualizeEntityBundle\Visualizer\VisualizerInterface[]
     */
    private $manualVisualizers;

    /**
     * @var \Linkin\Bundle\VisualizeEntityBundle\Visualizer\VisualizerInterface[]
     */
    private $visualizers;

    /**
     * @param \Linkin\Bundle\VisualizeEntityBundle\Visualizer\VisualizerInterface[] $visualizers
     * @param \Linkin\Bundle\VisualizeEntityBundle\Visualizer\VisualizerInterface[] $manualVisualizers
     */
    public function __construct(array $visualizers, array $manualVisualizers)
    {
        $this->manualVisualizers = $manualVisualizers;
        $this->visualizers       = $visualizers;
    }

    /**
     * Return visualization of the received data by first supported visualizer
     *
     * @param mixed       $value       Value for visualization
     * @param string|null $type        Type of the value
     * @param string|null $serviceName Prefer visualizer service
     * @param array       $options     Specific visualizer options
     * @param array       $context     Specific data
     *
     * @return string
     *
     * @throws VisualizerNotFoundException
     */
    public function visualize($value, $type = null, $serviceName = null, $options = [], $context = [])
    {
        if (is_null($type)) {
            $type = gettype($value);
        }

        if ($serviceName && array_key_exists($serviceName, $this->manualVisualizers)) {
            $visualizer = $this->manualVisualizers[$serviceName];

            return $visualizer->visualize($value, $type, $options, $context);
        }

        foreach ($this->visualizers as $visualizer) {
            if ($visualizer->supports($type, $value, $context)) {
                return $visualizer->visualize($value, $type, $options, $context);
            }
        }

        throw new VisualizerNotFoundException($type);
    }
}
