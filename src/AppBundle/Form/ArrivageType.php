<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class ArrivageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('fournisseur', EntityType::class, array(
            'class' => 'AppBundle:Fournisseur',
            'choice_label' => 'name',
        ))
        ->add('dateCreation', DateType::class, array(
            'widget' => 'single_text',
            //'format' => 'dd/MM/yyyy',
            'html5' => false,
            // adds a class that can be selected in JavaScript
            //'attr' => ['class' => 'js-datepicker'],
        ))
        ->add('elementArrivages', CollectionType::class, array(
            'entry_type' => ElementArrivageType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'label'=> false,
            'error_bubbling' => false,
            'entry_options' => array(
              'label' => false
            )
        ))
        ->add('note', TextareaType::class, array(
            'required' => false,
             'attr' =>
                array(
                  'placeholder' => 'Pour cet arrivage, Stephane a oublié d\'envoyer les coordonnées de ... ',
                ),
            //'empty_data' => 'John Doe',
            //'placeholder' => 'Last Name'
        ))
        ;

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Arrivage'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_arrivage';
    }


}
