<?php

namespace MAD\ExperienceBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject', 'entity', array(
                'class' => 'MADExperienceBundle:Subject',
                'empty_value' => 'Elige el bloque'
            ))
            ->add('title', 'text', array('label' => 'Título'))
            ->add('wording', 'textarea', array('label' => 'Enunciado'))
            ->add('groups', 'entity', array(
                'label' => 'Grupos',
                'class' => 'MADUserBundle:Group',
                'property' => 'name',
                'multiple' => true,
                'query_builder' => function(EntityRepository $er) {
                    $qb = $er->createQueryBuilder('g');
                    return $qb;
//                        ->where($qb->expr()->like('u.roles', ':roleKey'))
//                        ->setParameter('roleKey','%ROLE_TECHNICIAN%');
                }
            ))
            ;
    }

    public function getName()
    {
        return 'question';
    }
}