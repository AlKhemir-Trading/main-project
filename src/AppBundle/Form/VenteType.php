<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

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
            'choice_label' => function ($client) {
                return $client->getName(). " (".$client->getZone().")";
            },
            'placeholder' => 'Selectionnez Un Client',
            'empty_data'  => null,
            'required' => true

        ))
        ->add('date', DateType::class, array(
            'widget' => 'single_text',
            //'format' => 'dd/MM/yyyy',
            'html5' => false,
            // adds a class that can be selected in JavaScript
            //'attr' => ['class' => 'js-datepicker'],
        ))
        //->add('montant')
        ->add('montant', NumberType::class, array(
          //"grouping" => true,
          //'data' => 0.000,
          //'currency' => '',
          // 'scale' => 3,
          'required' => false,
          'attr' => array(
            "min" => 0,
            "step" => 0.100,
            "placeholder" => "0.000",
            //"onchange"=>"(function(el){el.value=parseFloat(el.value).toFixed(3);})(this)"
          )
        ))
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
