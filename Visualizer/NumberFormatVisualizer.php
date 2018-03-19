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
 * Provides visualization for the numeric values by using number_format function
 * @link http://php.net/manual/en/function.number-format.php
 *
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class NumberFormatVisualizer implements VisualizerInterface
{
    /**
     * Offset name for set number of decimal points.
     */
    const OPTION_DECIMALS = 'decimals';

    /**
     * Offset name for set separator for the decimal point
     */
    const OPTION_DECIMAL_SEPARATOR = 'decimal_separator';

    /**
     * Offset name for set thousands separator
     */
    const OPTION_THOUSAND_SEPARATOR = 'thousand_separator';

    /**
     * {@inheritDoc}
     */
    public function supports($type, $value = null, array $context = [])
    {
        $allowedTypes = ['int', 'integer', 'smallint', 'bigint', 'decimal', 'double', 'float'];

        return is_numeric($value) || in_array($type, $allowedTypes);
    }

    /**
     * {@inheritDoc}
     */
    public function visualize($value, $type = null, array $options = [], array $context = [])
    {
        $decimals = array_key_exists(self::OPTION_DECIMALS, $options)
            ? $options[self::OPTION_DECIMALS]
            : 0
        ;

        $decimalsSeparator = array_key_exists(self::OPTION_DECIMAL_SEPARATOR, $options)
            ? $options[self::OPTION_DECIMAL_SEPARATOR]
            : '.'
        ;

        $thousandSeparator = array_key_exists(self::OPTION_THOUSAND_SEPARATOR, $options)
            ? $options[self::OPTION_THOUSAND_SEPARATOR]
            : ','
        ;

        return number_format($value, $decimals, $decimalsSeparator, $thousandSeparator);
    }
}
