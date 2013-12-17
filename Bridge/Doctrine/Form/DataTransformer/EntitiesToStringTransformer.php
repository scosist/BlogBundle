<?php

namespace Stfalcon\Bundle\BlogBundle\Bridge\Doctrine\Form\DataTransformer;

use Stfalcon\Bundle\BlogBundle\Entity\TagManager;
use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * Transformer entities to string
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class EntitiesToStringTransformer implements DataTransformerInterface
{

    /**
     * @var TagManager
     */
    protected $manager;

    /**
     * Constructor injection. Set entity manager to object
     *
     * @param TagManager $manager Tag manager
     */
    public function __construct(TagManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Transforms tags entities into string (separated by comma)
     *
     * @param Collection|null $collection A collection of entities or NULL
     *
     * @return string|null An string of tags or NULL
     *
     * @throws \Symfony\Component\Form\Exception\UnexpectedTypeException
     */
    public function transform($collection)
    {
        if (null === $collection) {
            return null;
        }

        if (!($collection instanceof Collection)) {
            throw new UnexpectedTypeException($collection, 'Doctrine\Common\Collections\Collection');
        }

        $array = array();
        foreach ($collection as $entity) {
            $array[] = $entity->getText();
        }

        return implode(', ', $array);
    }

    /**
     * Transforms string into tags entities
     *
     * @param string|null $data Input string data
     *
     * @return Collection|null
     *
     * @throws \Symfony\Component\Form\Exception\UnexpectedTypeException
     */
    public function reverseTransform($data)
    {
        $collection = new ArrayCollection();

        if ('' === $data || null === $data) {
            return $collection;
        }

        if (!is_string($data)) {
            throw new UnexpectedTypeException($data, 'string');
        }

        foreach ($this->_stringToArray($data) as $text) {
            $tag = $this->manager->findTagBy(array('text' => $text));
            if (!$tag) {
                $tag = $this->manager->create($text);
                $this->manager->save($tag);
            }
            $collection->add($tag);
        }

        return $collection;
    }

    /**
     * Convert string of tags to array
     *
     * @param string $string
     *
     * @return array
     */
    private function _stringToArray($string)
    {
        $tags = explode(',', $string);
        // strip whitespaces from beginning and end of a tag text
        foreach ($tags as &$text) {
            $text = trim($text);
        }

        // removes duplicates
        return array_unique($tags);
    }

}