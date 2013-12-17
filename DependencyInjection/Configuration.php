<?php
namespace Stfalcon\Bundle\BlogBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class contains the configuration information for the bundle
 *
 * This information is solely responsible for how the different configuration
 * sections are normalized, and merged.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('stfalcon_blog');

        $rootNode->children()
            ->scalarNode('disqus_shortname')->isRequired()->cannotBeEmpty()->end()
            ->arrayNode('rss')
                ->addDefaultsIfNotSet()
                ->canBeUnset()
                ->children()
                    ->scalarNode('title')->defaultNull()->end()
                    ->scalarNode('description')->defaultNull()->end()
                ->end()
            ->end();


        $this->addPostSection($rootNode);
        $this->addTagSection($rootNode);

        return $treeBuilder;
    }

    private function addPostSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('post')
                    ->children()
                        ->scalarNode('entity')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('manager')->defaultValue('stfalcon_blog.post.manager.default')->end()
                        ->arrayNode('admin')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->defaultValue('Stfalcon\\Bundle\\BlogBundle\\Admin\\PostAdmin')->end()
                                ->scalarNode('controller')->defaultValue('SonataAdminBundle:CRUD')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addTagSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('tag')
                    ->children()
                        ->scalarNode('entity')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('manager')->defaultValue('stfalcon_blog.tag.manager.default')->end()
                        ->arrayNode('admin')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->defaultValue('Stfalcon\\Bundle\\BlogBundle\\Admin\\TagAdmin')->end()
                                ->scalarNode('controller')->defaultValue('SonataAdminBundle:CRUD')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
