<?php

namespace Stfalcon\Bundle\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Stfalcon\Bundle\BlogBundle\Entity\Tag
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 *
 * @ORM\MappedSuperclass
 */
class Tag
{
    /**
     * Tag text
     *
     * @var string $text
     * @Assert\NotBlank()
     * @ORM\Column(name="text", type="string", length=255)
     */
    protected $text = '';

    /**
     * @var ArrayCollection
     */
    protected $posts;

    /**
     * Get Tag id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Tag text
     *
     * @param string $text A tag text
     *
     * @return void
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * Get Tag text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * This method allows a class to decide how it will react when it is treated like a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getText()?$this->getText():'';
    }
}