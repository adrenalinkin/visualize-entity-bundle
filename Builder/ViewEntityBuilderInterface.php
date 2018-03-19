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

/**
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
interface ViewEntityBuilderInterface
{
    /**
     * Builds ViewEntity object by received configuration name and entity
     *
     * @param string     $configName Configuration name
     * @param object|int $entity     Entity object or entity ID
     *
     * @return \Linkin\Bundle\VisualizeEntityBundle\Entity\ViewEntity
     */
    public function buildViewEntity($configName, $entity);
}
