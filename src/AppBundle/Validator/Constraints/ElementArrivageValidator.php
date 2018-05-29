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
      else
        foreach ($value as $elementArrivage){
          if ($elementArrivage->getQuantite() <= 0 || $elementArrivage->getPrixUnit() <= 0 || $elementArrivage->getMontant() <=0 || is_null( $elementArrivage->getProduit() ) ){
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', "Verifiez votre saisi pour les elements d'arrivage.")
                ->addViolation();
          }
        }


    }
}
