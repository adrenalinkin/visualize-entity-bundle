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

use Symfony\Component\Translation\TranslatorInterface;

/**
 * Provides visualization for the boolean values
 *
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class BooleanVisualizer implements VisualizerInterface
{
    /**
     * Offset name for set true value option
     */
    const OPTION_TRUE = 'true_value';

    /**
     * Offset name for set false value option
     */
    const OPTION_FALSE = 'false_value';

    /**
     * Offset name for determine translate.
     *   - false:        disable translation
     *   - true or null: translate with default translation domain
     *   - string:       translate with received translation domain
     */
    const OPTION_TRANSLATE = 'translate';

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritDoc}
     */
    public function supports($type, $value = null, array $context = [])
    {
        return is_bool($value) || in_array($type, ['bool', 'boolean']);
    }

    /**
     * {@inheritDoc}
     */
    public function visualize($value, $type = null, array $options = [], array $context = [])
    {
        $trueValue = array_key_exists(self::OPTION_TRUE, $options)
            ? $options[self::OPTION_TRUE]
            : 'linkin_visualize_entity.labels.yes'
        ;

        $falseValue = array_key_exists(self::OPTION_FALSE, $options)
            ? $options[self::OPTION_FALSE]
            : 'linkin_visualize_entity.labels.no'
        ;

        $translate         = true;
        $translationDomain = null;

        if (array_key_exists(self::OPTION_TRANSLATE, $options)) {
            $translateFromOption = $options[self::OPTION_TRANSLATE];

            if (false === $translateFromOption) {
                $translate = false;
            } elseif (is_string($translateFromOption)) {
                $translationDomain = $translateFromOption;
            }
        }

        $value = (bool) $value ? $trueValue : $falseValue;

        if ($translate) {
            $value = $this->translator->trans($value, [], $translationDomain);
        }

        return $value;
    }
}
