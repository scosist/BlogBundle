<?php

namespace Stfalcon\Bundle\BlogBundle\Bridge\Doctrine\Form\Type;

use Doctrine\ORM\EntityManager;
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
    /** @var  EntityManager $em */
    protected $em;
    /** @var  string $class */
    protected $class;

    /**
     * Constructor injection
     *
     * @param EntityManager $em
     * @param string        $class
     */
    public function __construct(EntityManager $em, $class)
    {
        $this->em = $em;
        $this->class = $class;
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
            new EntitiesToStringTransformer($this->em, $this->class)
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