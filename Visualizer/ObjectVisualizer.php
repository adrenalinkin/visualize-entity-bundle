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
 * Provides visualization for the object values
 *
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class ObjectVisualizer implements VisualizerInterface
{
    /**
     * Offset name for set array with getters
     */
    const OPTION_GETTERS = 'getters_array';

    /**
     * Offset name for set template
     */
    const OPTION_TEMPLATE = 'template';

    /**
     * {@inheritDoc}
     */
    public function supports($type, $value = null, array $context = [])
    {
        return is_object($value) || in_array($type, ['object']);
    }

    /**
     * {@inheritDoc}
     */
    public function visualize($value, $type = null, array $options = [], array $context = [])
    {
        $getters  = array_key_exists(self::OPTION_GETTERS, $options) ? $options[self::OPTION_GETTERS] : null;
        $template = array_key_exists(self::OPTION_TEMPLATE, $options) ? $options[self::OPTION_TEMPLATE] : null;

        if (is_null($getters)) {
            return method_exists($value, '__toString') ? (string) $value : 'Object';
        }

        if (is_string($getters)) {
            $getters = [$getters];
        }

        // build default template if not set manually
        if (empty($template)) {
            $template = implode(' ', $getters);
        }

        foreach ($getters as $getter) {
            if (method_exists($value, $getter)) {
                $template = str_replace($getter, $value->$getter(), $template);
            }
        }

        return $template;
    }
}
