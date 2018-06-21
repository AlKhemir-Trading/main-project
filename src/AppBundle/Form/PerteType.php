<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class PerteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('perdu', IntegerType::class, array(
          //'data' => 0.000,
          //'scale' => 3,
          'attr' => array(
            "min" => 0,
            // "scale" => 3,
            // "step" => 0.001,
             "placeholder" => "0",
          )
        ))
        ->add('raison', TextareaType::class, array(
          'attr' => array(
            //'class' => 'tinymce'
          ),
          'required' => false,
        ))
        ->add('elementArrivage', EntityType::class, array(
            'class' => 'AppBundle:ElementArrivage',
            'choice_label' => 'id',
            'label' => false,
            'attr' => array(
              "readonly" => "readonly",
              "style" => "display:none"
            )
        ))
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Perte'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_perte';
    }


}
