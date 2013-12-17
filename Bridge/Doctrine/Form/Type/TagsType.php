<?php

namespace Stfalcon\Bundle\BlogBundle\Bridge\Doctrine\Form\Type;

use Stfalcon\Bundle\BlogBundle\Entity\TagManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Stfalcon\Bundle\BlogBundle\Bridge\Doctrine\Form\DataTransformer\EntitiesToStringTransformer;

/**
 * Form type for tags
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class TagsType extends AbstractType
{

    protected $tagManager;

    /**
     * Constructor injection
     *
     * @param TagManager $manager Tag manager
     */
    public function __construct(TagManager $manager)
    {
        $this->tagManager = $manager;
    }

    /**
     * Builds the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(
            new EntitiesToStringTransformer($this->tagManager)
        );
    }

    /**
     * Returns the name of the parent type.
     *
     * @return string
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'tags';
    }
}