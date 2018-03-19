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
 * Provides visualization for the values with precision
 *
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class DecimalVisualizer implements VisualizerInterface
{
    /**
     * Offset name for set round mode, use one of the mode constants @link http://php.net/manual/en/function.round.php
     */
    const OPTION_ROUND_METHOD = 'round_mode';

    /**
     * Offset name for set round precision
     */
    const OPTION_ROUND_PRECISION = 'round_precision';

    /**
     * {@inheritDoc}
     */
    public function supports($type, $value = null, array $context = [])
    {
        return is_float($value) || in_array($type, ['decimal', 'double', 'float']);
    }

    /**
     * {@inheritDoc}
     */
    public function visualize($value, $type = null, array $options = [], array $context = [])
    {
        $value = (float) $value;

        $precision = array_key_exists(self::OPTION_ROUND_PRECISION, $options)
            ? $options[self::OPTION_ROUND_PRECISION]
            : null
        ;

        $mode = array_key_exists(self::OPTION_ROUND_PRECISION, $options)
            ? $options[self::OPTION_ROUND_PRECISION]
            : null
        ;

        if (is_null($precision) && is_null($mode)) {
            return $value;
        }

        if (is_null($mode)) {
            return round($value, (int) $precision);
        }

        return round($value, (int) $precision, $mode);
    }
}
