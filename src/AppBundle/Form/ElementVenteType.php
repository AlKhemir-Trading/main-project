<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ElementVenteType extends AbstractType
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
          'required' => false,
          'empty_data' => 0,
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
          // 'empty_data' => 0,
          'required' => false,
          'attr' => array(
            "min" => 0,
            "step" => 0.001,
            "placeholder" => "0.000",
            //"onchange"=>"(function(el){el.value=parseFloat(el.value).toFixed(3);})(this)"
          )
        ))
        ->add('montant', NumberType::class, array(
          //"grouping" => true,
          //'data' => 0.000,
          //'currency' => '',
          // 'scale' => 3,
          'required' => false,
          'attr' => array(
            "min" => 0,
            "step" => 0.001,
            "placeholder" => "0.000",
            //"onchange"=>"(function(el){el.value=parseFloat(el.value).toFixed(3);})(this)"
          )
        ))
        // ->add('vente')
        // ->add('elementArrivage');
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ElementVente'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_elementvente';
    }


}
