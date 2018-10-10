<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ElementArrivageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('quantite', IntegerType::class, array(
          //'data' => 0.000,
          //'scale' => 3,
          'attr' => array(
            "min" => 0,
            // "scale" => 3,
            // "step" => 0.001,
             "placeholder" => "0",
          )
        ))

        ->add('prixUnit', NumberType::class, array(
          //"grouping" => true,
          //'data' => 0.000,
          //'currency' => '',
          // 'scale' => 3,
          'attr' => array(
            "min" => 0,
            "step" => 1,
            "placeholder" => "0.000",
            //"onchange"=>"(function(el){el.value=parseFloat(el.value).toFixed(3);})(this)"
          )
        ))

        ->add('montant', NumberType::class, array(
          //'data' => 0.000,
          // 'scale' => 3,
          'attr' => array(
            "min" => 0,
            "step" => 0.1,
            // "placeholder" => "0.000",
            //"onchange"=>"(function(el){el.value=parseFloat(el.value).toFixed(3);})(this)"
          )
        ))

        //->add('arrivage')
        ->add('produit', EntityType::class, array(
            // looks for choices from this entity
            'class' => 'AppBundle:Produit',

            // uses the User.username property as the visible option string
            'choice_label' => 'name',

            // used to render a select box, check boxes or radios
            // 'multiple' => true,
            // 'expanded' => true,
        ))
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ElementArrivage'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_elementarrivage';
    }


}
