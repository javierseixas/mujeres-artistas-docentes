<?php

namespace MAD\ExperienceBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class ExperienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('account', 'textarea', array('label' => 'Tu experiencia'))
            ;
    }

    public function getName()
    {
        return 'experience';
    }
}