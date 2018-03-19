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

use Doctrine\DBAL\Types;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Provides visualization for the @see \DateTimeInterface values
 *
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class DateTimeVisualizer implements VisualizerInterface
{
    /**
     * Offset name for set format
     */
    const OPTION_FORMAT = 'format';

    /**
     * Object of the current Database platform
     *
     * @var \Doctrine\DBAL\Platforms\AbstractPlatform
     */
    private $platform;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->platform = $em->getConnection()->getDatabasePlatform();
    }

    /**
     * {@inheritDoc}
     */
    public function supports($type, $value = null, array $context = [])
    {
        $allowedTypes = [
            'date',
            'datetime',
            'time',
            'date_immutable',
            'datetime_immutable',
            'time_immutable',
            'datetimetz',
            'datetimetz_immutable',
        ];

        return $value instanceof \DateTimeInterface || in_array($type, $allowedTypes);
    }

    /**
     * {@inheritDoc}
     */
    public function visualize($value, $type = null, array $options = [], array $context = [])
    {
        $dateFormat = array_key_exists(self::OPTION_FORMAT, $options)
            ? $options[self::OPTION_FORMAT]
            : $this->getDefaultFormat($type)
        ;

        if ($value instanceof \DateTimeInterface) {
            return $value->format($dateFormat);
        }

        if (is_numeric($value)) {
            $value = \DateTime::createFromFormat('U', $value);

            return $value->format($dateFormat);
        }

        /** @var Types\DateTimeType|Types\DateTimeTzType|Types\DateType|Types\TimeType $type */
        $type  = Types\Type::getType($type);
        $value = date($dateFormat, strtotime($value));
        $value = $type->convertToPHPValue($value, $this->platform);

        return $value->format($dateFormat);
    }

    /**
     * Returns default format string according to received type
     *
     * @param string $type
     *
     * @return string
     */
    private function getDefaultFormat($type)
    {
        if (in_array($type, ['date', 'date_immutable'])) {
            return $this->platform->getDateFormatString();
        }

        if (in_array($type, ['time', 'time_immutable'])) {
            return $this->platform->getTimeFormatString();
        }

        if (in_array($type, ['datetimetz', 'datetimetz_immutable'])) {
            return $this->platform->getDateTimeTzFormatString();
        }

        return $this->platform->getDateTimeFormatString();
    }
}
