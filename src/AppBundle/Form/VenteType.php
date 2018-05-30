<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class VenteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('client', EntityType::class, array(
            'class' => 'AppBundle:Client',
            'choice_label' => 'name',
        ))
        ->add('date', DateType::class, array(
            'widget' => 'single_text',
            //'format' => 'dd/MM/yyyy',
            'html5' => false,
            // adds a class that can be selected in JavaScript
            //'attr' => ['class' => 'js-datepicker'],
        ))
        //->add('montant')
        ->add('elementsVente', CollectionType::class, array(
            'entry_type' => ElementVenteType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'error_bubbling' => false,
            'label'=> false,
            'entry_options' => array(
              'label' => false
            )
        ));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Vente'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_vente';
    }


}
