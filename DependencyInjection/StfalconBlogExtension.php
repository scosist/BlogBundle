<?php

namespace Stfalcon\Bundle\BlogBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;

/**
 * This is the class that loads and manages StfalconBlogBundle configuration
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class StfalconBlogExtension extends Extension
{

    /**
     * Load configuration from services.xml
     *
     * @param array            $configs   An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     *
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $container->setParameter('stfalcon_blog.disqus_shortname', $config['disqus_shortname']);
        $container->setParameter('stfalcon_blog.rss.title', $config['rss']['title']);
        $container->setParameter('stfalcon_blog.rss.description', $config['rss']['description']);

        $loader->load('orm.xml');
        $container->setParameter('stfalcon_blog.post.entity', $config['post']['entity']);
        $container->setAlias('stfalcon_blog.post.manager', $config['post']['manager']);
        unset($config['post']['manager']);

        $container->setParameter('stfalcon_blog.tag.entity', $config['tag']['entity']);
        $container->setAlias('stfalcon_blog.tag.manager', $config['tag']['manager']);
        unset($config['tag']['manager']);

        $loader->load('admin.xml');

        $container->setParameter('stfalcon_blog.post.admin.class', $config['post']['admin']['class']);
        $container->setParameter('stfalcon_blog.post.admin.controller', $config['post']['admin']['controller']);

        $container->setParameter('stfalcon_blog.tag.admin.class', $config['tag']['admin']['class']);
        $container->setParameter('stfalcon_blog.tag.admin.controller', $config['tag']['admin']['controller']);
        $loader->load('services.xml');
    }

}