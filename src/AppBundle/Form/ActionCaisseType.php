<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ActionCaisseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('montant', NumberType::class, array(
          //"grouping" => true,
          //'data' => 0.000,
          //'currency' => '',
          // 'scale' => 3,
          'required' => true,
          'attr' => array(
            "min" => 0,
            "step" => 0.100,
            "placeholder" => "0.000",
            //"onchange"=>"(function(el){el.value=parseFloat(el.value).toFixed(3);})(this)"
          )
        ))
        // ->add('date', DateType::class, array(
        //     'widget' => 'single_text',
        //     //'format' => 'dd/MM/yyyy',
        //     'html5' => false,
        //     // adds a class that can be selected in JavaScript
        //     //'attr' => ['class' => 'js-datepicker'],
        // ))
        ->add('motif')
        ->add('type',ChoiceType::class, array(
            'choices'  => array(
                'Depot' => 'depot',
                'Retrait' => 'retrait',
            ),
            'attr' => array('class' => 'btn-primary'),
        ))
        //->add('user')
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ActionCaisse'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_actioncaisse';
    }


}
