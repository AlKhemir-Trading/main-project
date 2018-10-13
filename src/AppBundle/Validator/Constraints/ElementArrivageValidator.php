<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ElementArrivageValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
      if (count($value) < 1)
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', "Vous devez saisir au moins un element d'arrivage.")
            ->addViolation();
      else{
        $products_ids = array();
        foreach ($value as $elementArrivage){
          // die('qq'.is_null( $elementArrivage->getProduit() ));
          // || $elementArrivage->getPrixUnit() <= 0 || $elementArrivage->getMontant() <=0
          if ($elementArrivage->getQuantite() <= 0  || is_null( $elementArrivage->getProduit() ) ){
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', "Verifiez votre saisi pour les elements d'arrivage.")
                ->addViolation();
          }else{
            $products_ids[] = $elementArrivage->getProduit()->getName();
          }
        }

        $products_non_duplicated = array();
        foreach($products_ids as $product){
          if( !in_array($product,$products_non_duplicated) )
            $products_non_duplicated[] = $product;
          else
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', "Le produit ( $product ) est dupliqué! Veuillez rectifiez votre saisie. ")
                ->addViolation();
        }

      }

    }
}
