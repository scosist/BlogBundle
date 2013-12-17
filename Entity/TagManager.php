<?php
namespace Stfalcon\Bundle\BlogBundle\Entity;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;

/**
 * Tag manager uses for work with tags
 */
class TagManager
{
    protected $objectManager;
    protected $class;
    protected $repository;

    /**
     * Constructor.
     *
     * @param ObjectManager $om
     * @param string        $class
     */
    public function __construct(ObjectManager $om, $class)
    {
        $this->objectManager = $om;
        $metadata = $om->getClassMetadata($class);
        $this->class = $metadata->getName();
        $this->repository = $om->getRepository($class);
    }

    /**
     * Create new category
     *
     * @param string|null $text
     *
     * @return mixed
     */
    public function create($text = null)
    {
        return new $this->class($text);
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
     * Find one tag by id
     *
     * @param int $id
     *
     * @return object
     */
    public function findTag($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Find one tag by criteria
     *
     * @param array $criteria
     *
     * @return object
     */
    public function findTagBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Find tags by criteria
     *
     * @param array $criteria
     *
     * @return array
     */
    public function findTagsBy(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * Find all tags
     *
     * @return mixed
     */
    public function findAllTags()
    {
        return $this->repository->findAll();
    }
}
