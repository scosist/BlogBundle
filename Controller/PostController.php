<?php

namespace Stfalcon\Bundle\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * PostController
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class PostController extends AbstractController
{
    /**
     * List of posts for admin
     *
     * @param int $page Page number
     *
     * @return array
     *
     * @Route("/blog/{title}/{page}", name="blog",
     *      requirements={"page"="\d+", "title"="page"},
     *      defaults={"page"="1", "title"="page"})
     * @Template()
     */
    public function indexAction($page)
    {
        $allPostsQuery = $this->get('stfalcon_blog.post.manager')->findAllPostsAsQuery();
        $posts= $this->get('knp_paginator')->paginate($allPostsQuery, $page, 10);

        if ($this->has('application_default.menu.breadcrumbs')) {
            $breadcrumbs = $this->get('application_default.menu.breadcrumbs');
            $breadcrumbs->addChild('Блог')->setCurrent(true);
        }

        return array(
            'posts' => $posts,
            'disqus_shortname' => $this->container->getParameter('stfalcon_blog.disqus_shortname')
        );
    }

    /**
     * View post
     *
     * @Route("/blog/post/{slug}", name="blog_post_view")
     * @Template()
     *
     * @param string $slug
     *
     * @return array
     *
     * @throws NotFoundHttpException
     */
    public function viewAction($slug)
    {
        $post = $this->get('stfalcon_blog.post.manager')->findPostBy(array('slug' => $slug));
        if (!$post) {
            throw new NotFoundHttpException();
        }
        if ($this->has('application_default.menu.breadcrumbs')) {
            $breadcrumbs = $this->get('application_default.menu.breadcrumbs');
            $breadcrumbs->addChild('Блог', array('route' => 'blog'));
            $breadcrumbs->addChild($post->getTitle())->setCurrent(true);
        }

        return array(
            'post' => $post,
            'disqus_shortname' => $this->container->getParameter('stfalcon_blog.disqus_shortname')
        );
    }

    /**
     * RSS feed
     *
     * @Route("/blog/rss", name="blog_rss")
     *
     * @return Response
     */
    public function rssAction()
    {
        $feed = new \Zend\Feed\Writer\Feed();

        $feed->setTitle($this->container->getParameter('stfalcon_blog.rss.title'));
        $feed->setDescription($this->container->getParameter('stfalcon_blog.rss.description'));
        $feed->setLink($this->generateUrl('blog_rss', array(), true));

        $posts = $this->get('stfalcon_blog.post.manager')->findAllPosts();
        foreach ($posts as $post) {
            $entry = new \Zend\Feed\Writer\Entry();
            $entry->setTitle($post->getTitle());
            $entry->setLink($this->generateUrl('blog_post_view', array('slug' => $post->getSlug()), true));

            $feed->addEntry($entry);
        }

        return new Response($feed->export('rss'));
    }

    /**
     * Show last blog posts
     *
     * @Template()
     *
     * @param int $count A count of posts
     *
     * @return array()
     */
    public function lastAction($count = 1)
    {
        $posts = $this->get('stfalcon_blog.post.manager')->findLastPosts($count);

        return array('posts' => $posts);
    }
}