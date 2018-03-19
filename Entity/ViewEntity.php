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
class ViewEntity
{
    /**
     * Original entity object
     *
     * @var object
     */
    private $entity;

    /**
     * Original entity id value
     *
     * @var int|string
     */
    private $entityId;

    /**
     * All registered fields
     *
     * @var ViewFieldData[]
     */
    private $fields = [];

    /**
     * @param object     $entity
     * @param int|string $entityId
     */
    public function __construct($entity, $entityId)
    {
        $this->entity   = $entity;
        $this->entityId = $entityId;
    }

    /**
     * Return original entity object
     *
     * @return object
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Return original entity id value
     *
     * @return int|string
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * Adds data for the received field name. If offset already exists then data will be overrides
     *
     * @param ViewFieldData $fieldData
     *
     * @return $this
     */
    public function addFieldData(ViewFieldData $fieldData)
    {
        $this->fields[$fieldData->getName()] = $fieldData;

        return $this;
    }

    /**
     * Returns array of the all registered ViewFieldData objects regardless has been displayed or was not
     *
     * @param array $except List of the field names which should be excepted from the result
     *
     * @return ViewFieldData[]
     */
    public function getAll(array $except = [])
    {
        $result = [];

        foreach ($this->fields as $fieldName => $fieldData) {
            if (in_array($fieldName, $except)) {
                continue;
            }

            $result[$fieldName] = $fieldData;
        }

        return $result;
    }

    /**
     * Returns array of the ViewFieldData objects which was already displayed
     *
     * @return ViewFieldData[]
     */
    public function getAlreadyDisplayed()
    {
        $result = [];

        foreach ($this->fields as $fieldName => $fieldData) {
            if (true === $fieldData->isDisplayed()) {
                $result[$fieldName] = $fieldData;
            }
        }

        return $result;
    }

    /**
     * Returns array of the ViewFieldData objects which was not displayed yet
     *
     * @return ViewFieldData[]
     */
    public function getNotDisplayed()
    {
        $result = [];

        foreach ($this->fields as $fieldName => $fieldData) {
            if (false === $fieldData->isDisplayed()) {
                $result[$fieldName] = $fieldData;
            }
        }

        return $result;
    }

    /**
     * Return ViewFieldData by field name
     *
     * @param string $fieldName Field name
     *
     * @return ViewFieldData|null
     */
    public function getFieldData($fieldName)
    {
        if (empty($this->fields[$fieldName])) {
            return null;
        }

        return $this->fields[$fieldName];
    }

    /**
     * Set all displayed fields as not displayed
     *
     * @return $this
     */
    public function resetDisplayed()
    {
        foreach ($this->fields as $fieldData) {
            $fieldData->resetDisplayed();
        }

        return $this;
    }
}
