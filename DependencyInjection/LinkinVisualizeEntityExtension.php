<?php

/*
 * This file is part of the LinkinVisualizeEntityBundle package.
 *
 * (c) Viktor Linkin <adrenalinkin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Linkin\Bundle\VisualizeEntityBundle\DependencyInjection;

use Linkin\Component\ConfigHelper\Extension\AbstractExtension;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;

/**
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class LinkinVisualizeEntityExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'linkin_visualize_entity';
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configs = $this->getConfigurationsFromFile('visualize_entity.yml', $container);
        $configs = $this->processConfiguration(new Configuration(), $configs);

        // save configuration into the container parameter
        $container->setParameter('linkin_visualize_entity.configurations', $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
