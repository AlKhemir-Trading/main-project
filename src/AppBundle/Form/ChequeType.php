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
use Symfony\Component\Form\Extension\Core\Type\TextType;


class ChequeType extends AbstractType
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
            // "disabled" => "disabled",
          )
        ))
        ->add('type', ChoiceType::class, array(
            'choices'  => array(
                'Cash' => 'cash',
                'Cheque' => 'cheque'
            ),
            'attr' => array(
              "readonly" => "readonly",
              // "disabled" => "disabled",
            )
        ))
        ->add('numCheque',TextType::class,array(
          'attr' => array(
              "readonly" => "readonly",
          )))
        ->add('pocesseur',TextType::class,array(
          'attr' => array(
              "readonly" => "readonly",
          )))
        ->add('banque',TextType::class,array(
          'attr' => array(
              "readonly" => "readonly",
          )))
        ->add('dateCheque', DateType::class, array(
            'widget' => 'single_text',
            //'format' => 'dd/MM/yyyy',
            'html5' => false,
            // adds a class that can be selected in JavaScript
            'attr' => ['readonly' => 'readonly'],
        ))
        ->add('montant', NumberType::class, array(
          //'data' => 0.000,
          // 'scale' => 3,
          'attr' => array(
            "min" => 0.001,
            "step" => 0.001,
            "placeholder" => "0.000",
            "readonly" => "readonly",
            //"onchange"=>"(function(el){el.value=parseFloat(el.value).toFixed(3);})(this)"
          )
        ))
        ->add('etatCheque', ChoiceType::class, array(
            'choices'  => array(
                'Payé' => 1,
                'Impayé' => 0
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
