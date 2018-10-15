<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;



class PayementType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('client',EntityType::class, array(
          'class' => 'AppBundle:Client',
          'choice_label' => 'name',
          'attr' => array(
            "readonly" => "readonly",
          )
        ))
        ->add('type', ChoiceType::class, array(
            'choices'  => array(
                'Cash' => 'cash',
                'Cheque' => 'cheque'
            ),
        ))
        ->add('numCheque')
        ->add('pocesseur')
        ->add('banque')
        ->add('dateCheque', DateType::class, array(
            'widget' => 'single_text',
            //'format' => 'dd/MM/yyyy',
            'html5' => false,
            // adds a class that can be selected in JavaScript
            //'attr' => ['class' => 'js-datepicker'],
        ))
        ->add('montant', NumberType::class, array(
          //'data' => 0.000,
          // 'scale' => 3,
          'attr' => array(
            "min" => 0,
            "step" => 0.001,
            "placeholder" => "0.000",
            //"onchange"=>"(function(el){el.value=parseFloat(el.value).toFixed(3);})(this)"
          )
        ))
        // ->add('note', TextareaType::class, array(
        //   'attr' => array(
        //     //'class' => 'tinymce'
        //   ),
        //   'required' => false,
        // ))
        ;

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Payement'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_payement';
    }


}
