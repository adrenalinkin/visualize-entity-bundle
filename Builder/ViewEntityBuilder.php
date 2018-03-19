<?php

/*
 * This file is part of the LinkinVisualizeEntityBundle package.
 *
 * (c) Viktor Linkin <adrenalinkin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Linkin\Bundle\VisualizeEntityBundle\Builder;

use Doctrine\ORM\EntityNotFoundException;

use Linkin\Bundle\EntityHelperBundle\Helper\EntityHelper;
use Linkin\Bundle\VisualizeEntityBundle\Entity\ViewEntity;
use Linkin\Bundle\VisualizeEntityBundle\Entity\ViewFieldData;
use Linkin\Bundle\VisualizeEntityBundle\Handler\VisualizerHandler;

use Symfony\Component\Translation\TranslatorInterface;

/**
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class ViewEntityBuilder implements ViewEntityBuilderInterface
{
    /**
     * All registered configurations
     *
     * @var array
     */
    private $configs = [];

    /**
     * Entity helper
     *
     * @var EntityHelper
     */
    private $entityHelper;

    /**
     * Translator instance
     *
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * Visualizer handler
     *
     * @var VisualizerHandler
     */
    private $visualizerHandler;

    /**
     * @param array               $configs
     * @param EntityHelper        $entityHelper
     * @param VisualizerHandler   $visualizerHandler
     * @param TranslatorInterface $translator
     */
    public function __construct(array $configs, EntityHelper $entityHelper, VisualizerHandler $visualizerHandler, TranslatorInterface $translator)
    {
        $this->configs           = $configs;
        $this->entityHelper      = $entityHelper;
        $this->translator        = $translator;
        $this->visualizerHandler = $visualizerHandler;
    }

    /**
     * {@inheritdoc}
     *
     * @throws EntityNotFoundException|\UnexpectedValueException
     */
    public function buildViewEntity($configName, $entityOrId)
    {
        if (!array_key_exists($configName, $this->configs)) {
            throw new \UnexpectedValueException(sprintf('Configuration with name "%s" not found', $configName));
        }

        $config    = $this->configs[$configName];
        $className = $config['className'];

        // determine entity and entity ID
        $entity   = $this->getEntity($entityOrId, $className);
        $entityId = $this->getEntityId($entityOrId, $className);

        // is label translation enabled
        $useLabelTranslation = isset($config['label_translation']);

        if ($useLabelTranslation) {
            $labelTranslationDomain = $config['label_translation']['domain'];
            $labelTranslationPrefix = $config['label_translation']['prefix'];
        } else {
            $labelTranslationDomain = null;
            $labelTranslationPrefix = null;
        }

        // initialize ViewEntity
        $viewEntity = new ViewEntity($entity, $entityId);
        $metadata   = $this->entityHelper->getEntityMetadata($entity);

        foreach ($config['fields'] as $fieldName => $fieldConfig) {
            $getter = empty($fieldConfig['getter']) ? 'get'.ucfirst($fieldName) : $fieldConfig['getter'];
            $value  = $entity->$getter();

            if (isset($metadata->fieldMappings[$fieldName])) {
                $type = $metadata->fieldMappings[$fieldName]['type'];
            } else {
                $type = gettype($value);
            }

            $visualizerName    = null;
            $visualizerOptions = [];

            if (isset($fieldConfig['visualizer_options'])) {
                $visualizerOptions = $fieldConfig['visualizer_options'];
            }

            // when visualizer has been determined manually
            if (isset($fieldConfig['visualizer'])) {
                $visualizerName = $fieldConfig['visualizer'];
            }

            $visualizedData = $this->visualizerHandler->visualize($value, $type, $visualizerName, $visualizerOptions);

            if (!empty($fieldConfig['label'])) {
                $label = $fieldConfig['label'];
            } elseif ($useLabelTranslation) {
                $label = $labelTranslationPrefix.$fieldName;
            } else {
                $label = $this->getLabelByDefault($fieldName);
            }

            if ($useLabelTranslation) {
                $label = $this->translator->trans($label, [], $labelTranslationDomain);
            }

            $viewFieldData = new ViewFieldData($fieldName, $label, $visualizedData);

            $viewEntity->addFieldData($viewFieldData);
        }

        return $viewEntity;
    }

    /**
     * Return entity by received object or identity
     *
     * @param object|int $entityOrId
     * @param string     $className
     *
     * @return object
     *
     * @throws EntityNotFoundException
     */
    private function getEntity($entityOrId, $className)
    {
        if (is_object($entityOrId)) {
            return $entityOrId;
        }

        $entity = $this->entityHelper->getEntityManager()->find($className, $entityOrId);

        if (!$entity) {
            throw new EntityNotFoundException(
                sprintf('Entity with received identity "%s" not found in DB', $entityOrId)
            );
        }

        return $entity;
    }

    /**
     * Return entity ID by received object or identity
     *
     * @param object|int $entityOrId
     * @param string     $className
     *
     * @return int|string
     *
     * @throws \UnexpectedValueException
     */
    private function getEntityId($entityOrId, $className)
    {
        if (is_scalar($entityOrId)) {
            return $entityOrId;
        }

        $receivedClassName = $this->entityHelper->getEntityClassShort($entityOrId);

        if ($receivedClassName !== $className) {
            throw new \UnexpectedValueException(
                sprintf('Expected entity class "%s", received  "%s" instead', $className, $receivedClassName)
            );
        }

        return $this->entityHelper->getEntityIdValue($entityOrId);
    }

    /**
     * Returns default label value
     *
     * @param string $label
     *
     * @return string
     */
    private function getLabelByDefault($label)
    {
        $pieces = preg_split('/(?=[A-Z])/', $label);

        return ucfirst(implode(' ', $pieces));
    }
}
