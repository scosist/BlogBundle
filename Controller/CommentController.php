<?php

namespace Stfalcon\Bundle\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Controller for actions with DISQUS comments
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class CommentController extends Controller
{

    /**
     * Synchronization comments count with disqus
     *
     * @param string $slug Slug of post
     *
     * @return Response
     * @Route("/blog/post/{slug}/disqus-sync", name="blog_post_disqus_sync")
     *
     * @throws NotFoundHttpException
     */
    public function disqusSyncAction($slug)
    {
        // @todo. нужно доставать полный список ЗААПРУВЛЕННЫХ комментариев или
        // колличество комментариев к записи (если такой метод появится в API disqus)
        // после чего обновлять их колличество в БД
        $post = $this->get('stfalcon_blog.post.manager')->findPostBy(array('slug' => $slug));
        if (!$post) {
            throw new NotFoundHttpException();
        }

        $post->setCommentsCount($post->getCommentsCount() + 1);
        $em = $this->get('doctrine.orm.entity_manager');
        $em->persist($post);
        $em->flush($post);

        return new Response(json_encode('ok'));
    }

}