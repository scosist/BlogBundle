<?php
namespace Stfalcon\Bundle\BlogBundle\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * Post manager uses for work with posts
 */
class PostManager
{
    protected $objectManager;
    protected $class;
    protected $repository;

    /**
     * Constructor.
     *
     * @param EntityManager $om
     * @param string        $class
     */
    public function __construct(EntityManager $om, $class)
    {
        $this->objectManager = $om;
        $metadata = $om->getClassMetadata($class);
        $this->class = $metadata->getName();
        $this->repository = $om->getRepository($class);
    }

    /**
     * Create new category
     *
     * @return mixed
     */
    public function create()
    {
        return new $this->class;
    }

    /**
     * Remove category
     *
     * @param mixed $category
     */
    public function delete($category)
    {
        $this->objectManager->remove($category);
        $this->objectManager->flush();
    }

    /**
     * Save category
     *
     * @param mixed $category
     */
    public function save($category)
    {
        $this->objectManager->persist($category);
        $this->objectManager->flush();
    }

    /**
     * Get class name
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return EntityRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Find one post by id
     *
     * @param int $id
     *
     * @return object
     */
    public function findPost($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Find one post by criteria
     *
     * @param array $criteria
     *
     * @return object
     */
    public function findPostBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Find posts by criteria
     *
     * @param array $criteria
     *
     * @return array
     */
    public function findPostsBy(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * Find all posts
     *
     * @return mixed
     */
    public function findAllPosts()
    {
        return $this->findAllPostsAsQuery()->getResult();
    }

    /**
     * Find all posts as query
     *
     * @return mixed
     */
    public function findAllPostsAsQuery()
    {
        return $this->repository->createQueryBuilder('p')
            ->orderBy('p.created', 'DESC')
            ->getQuery();
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
        return $this->repository->createQueryBuilder('p')
            ->join('p.tags', 't')
            ->where('t = :tag')
            ->orderBy('p.created', 'DESC')
            ->setParameter('tag', $tag)
            ->getQuery();
    }

    /**
     * Get last posts
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
}
