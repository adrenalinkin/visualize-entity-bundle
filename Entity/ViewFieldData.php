<?php

/*
 * This file is part of the LinkinVisualizeEntityBundle package.
 *
 * (c) Viktor Linkin <adrenalinkin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Linkin\Bundle\VisualizeEntityBundle\Entity;

/**
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class ViewFieldData
{
    /**
     * Flag is data has been displayed
     *
     * @var bool
     */
    private $isDisplayed = false;

    /**
     * Field's label
     *
     * @var string
     */
    private $label;

    /**
     * Field's name
     *
     * @var string
     */
    private $name;

    /**
     * Field's value
     *
     * @var string|int|float
     */
    private $value;

    /**
     * @param string           $name
     * @param string           $label
     * @param string|int|float $value
     */
    public function __construct($name, $label, $value)
    {
        $this->label = $label;
        $this->name  = $name;
        $this->value = $value;
    }

    /**
     * Return is field data has been displayed
     *
     * @return bool
     */
    public function isDisplayed()
    {
        return $this->isDisplayed;
    }

    /**
     * Reset displayed flag to false
     *
     * @return $this
     */
    public function resetDisplayed()
    {
        $this->isDisplayed = false;

        return $this;
    }

    /**
     * Return field label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Return field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Return field value
     *
     * @param bool $markAsDisplayed
     *
     * @return float|int|string
     */
    public function getValue($markAsDisplayed = true)
    {
        $this->isDisplayed = (bool) $markAsDisplayed;

        return $this->value;
    }
}
