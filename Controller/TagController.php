<?php

namespace Stfalcon\Bundle\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * TagController
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class TagController extends Controller
{

    /**
     * View tag
     *
     * @Route("/blog/tag/{text}/{title}/{page}", name="blog_tag_view",
     *      requirements={"page"="\d+", "title"="page"},
     *      defaults={"page"="1", "title"="page"})
     * @Template()
     *
     * @param string $text text of tag
     * @param int    $page page number
     *
     * @return array
     *
     * @throws NotFoundHttpException
     */
    public function viewAction($text, $page)
    {
        $tag = $this->get('stfalcon_blog.tag.repository')->findOneBy(array('text' => $text));
        if (!$tag) {
            throw new NotFoundHttpException();
        }

        $postsQuery = $this->get('stfalcon_blog.post.repository')->findPostsByTagAsQuery($tag);
        $posts = $this->get('knp_paginator')
            ->paginate($postsQuery, $page, 10);

        if ($this->has('menu.breadcrumbs')) {
            $breadcrumbs = $this->get('menu.breadcrumbs');
            $breadcrumbs->addChild('Блог', $this->get('router')->generate('blog'));
            $breadcrumbs->addChild($tag->getText())->setIsCurrent(true);
        }

        return array(
            'tag' => $tag,
            'posts' => $posts,
            'disqus_shortname' => $this->container->getParameter('stfalcon_blog.disqus_shortname')
        );
    }

}