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

use Doctrine\Common\Collections\Collection;

/**
 * Provides visualization for the doctrine collections
 *
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class DoctrineCollectionVisualizer extends ObjectVisualizer
{
    /**
     * Offset name set delimiter option
     */
    const OPTION_DELIMITER = 'delimiter';

    /**
     * Offset name for set limit for loading collection items
     */
    const OPTION_LIMIT = 'limit';

    /**
     * {@inheritDoc}
     */
    public function supports($type, $value = null, array $context = [])
    {
        return $value instanceof Collection;
    }

    /**
     * {@inheritDoc}
     * @param Collection $value
     */
    public function visualize($value, $type = null, array $options = [], array $context = [])
    {
        $delimiter = array_key_exists(self::OPTION_DELIMITER, $options) ? $options[self::OPTION_DELIMITER] : ', ';
        $limit     = array_key_exists(self::OPTION_LIMIT, $options) ? $options[self::OPTION_LIMIT] : 30;

        // load all collection or only limited part
        $entities = is_null($limit) ? $value->toArray() : $value->slice(0, $limit + 1);
        $counter  = 0;
        $items    = [];

        foreach ($entities as $entity) {
            if ($counter > $limit) {
                $items[] = '...';

                break;
            }

            $items[] = parent::visualize($entity, $type, $options, $context);
            ++$counter;
        }

        return implode($delimiter, $items);
    }
}
