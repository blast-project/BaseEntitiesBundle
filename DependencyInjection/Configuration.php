<?php

/*
 * This file is part of the BLAST package <http://blast.libre-informatique.fr>.
 *
 * Copyright (C) 2015-2016 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Blast\BaseEntitiesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('blast_base_entities');

        $rootNode
            ->append($this->getVendorNode('orm'))
            ->append($this->getVendorNode('mongodb')) // not tested yet
            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * @param string $name
     */
    private function getVendorNode($name)
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root($name);

        $node
            ->useAttributeAsKey('id')
            ->prototype('array')
                ->children()
                    ->scalarNode('naming')->defaultFalse()->end()
                    ->scalarNode('guidable')->defaultFalse()->end()
                    ->scalarNode('timestampable')->defaultFalse()->end()
                    ->scalarNode('addressable')->defaultFalse()->end()
                    ->scalarNode('treeable')->defaultFalse()->end()
                    ->scalarNode('nameable')->defaultFalse()->end()
                    ->scalarNode('labelable')->defaultFalse()->end()
                    ->scalarNode('emailable')->defaultFalse()->end()
                    ->scalarNode('descriptible')->defaultFalse()->end()
                    ->scalarNode('searchable')->defaultFalse()->end()
                    ->scalarNode('loggable')->defaultFalse()->end()
                    ->scalarNode('sortable')->defaultFalse()->end()
                    ->scalarNode('normalize')->defaultFalse()->end()
                    ->scalarNode('nested_treeable')->defaultFalse()->end()
                    ->scalarNode('syliusGuidable')->defaultFalse()->end()
                ->end()
            ->end()
        ;

        return $node;
    }
}