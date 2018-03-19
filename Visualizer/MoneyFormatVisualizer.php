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
 * Provides visualization for the numeric values by using money_format function
 * @link http://php.net/manual/en/function.money-format.php
 *
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class MoneyFormatVisualizer implements VisualizerInterface
{
    /**
     * Offset name for set format specification.
     */
    const OPTION_FORMAT = 'format';

    /**
     * Offset name for set locale
     */
    const OPTION_LOCALE = 'locale';

    /**
     * {@inheritDoc}
     */
    public function supports($type, $value = null, array $context = [])
    {
        $allowedFormats = ['int', 'integer', 'smallint', 'bigint', 'decimal', 'double', 'float'];

        return is_numeric($value) || in_array($type, $allowedFormats);
    }

    /**
     * {@inheritDoc}
     */
    public function visualize($value, $type = null, array $options = [], array $context = [])
    {
        $format = array_key_exists(self::OPTION_FORMAT, $options)
            ? $options[self::OPTION_FORMAT]
            : '%i'
        ;

        $originalLocale = setlocale(LC_MONETARY, 0);

        if (array_key_exists(self::OPTION_LOCALE, $options)) {
            setlocale(LC_MONETARY, $options[self::OPTION_LOCALE]);
        }

        $value = money_format($format, $value);

        setlocale(LC_MONETARY, $originalLocale);

        return $value;
    }
}
