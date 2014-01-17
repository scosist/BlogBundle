<?php

namespace Stfalcon\Bundle\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PostRepository
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class PostRepository extends EntityRepository
{

    /**
     * Find all posts
     *
     * @return array
     */
    public function findAllPosts()
    {
        $query = $this->findAllPostsAsQuery();

        return $query->getResult();
    }

    /**
     * @return mixed
     */
    public function findAllPostsAsQuery()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.created', 'DESC')
            ->getQuery();
    }

    /**
     * Find last posts
     *
     * @param integer $count Max count of returned posts
     *
     * @return array
     */
    public function findLastPosts($count = null)
    {
        $query = $this->findAllPostsAsQuery();
        if ((int) $count) {
            $query->setMaxResults($count);
        }

        return $query->getResult();
    }

    /**
     * Find posts by tag as query
     *
     * @param mixed $tag
     *
     * @return mixed
     */
    public function findPostsByTagAsQuery($tag)
    {
        return $this->createQueryBuilder('p')
            ->join('p.tags', 't')
            ->where('t = :tag')
            ->orderBy('p.created', 'DESC')
            ->setParameter('tag', $tag)
            ->getQuery();
    }

}