<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ElementVenteValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
      // new Collection
      $elementsVente = array();
      foreach( $value as $element){
         if ( $element->getQuantite() != 0 || $element->getPrixUnit() != 0 )
          $elementsVente[] = $element;
      }

      if (count($elementsVente) < 1)
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', "Vous devez saisir au moins un element de vente.")
            ->addViolation();
      else{
        foreach ($elementsVente as $elementVente){
          if ($elementVente->getQuantite() <= 0 || $elementVente->getPrixUnit() <= 0 || $elementVente->getMontant() <=0
              || ( ($element->getQuantite() == 0 && $element->getPrixUnit() != 0)
                    || ($element->getQuantite() != 0 && $element->getPrixUnit() == 0) )  ){
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', "Verifiez votre saisi pour les elements de vente.")
                ->addViolation();
          }
          elseif ( $elementVente->getQuantite() > $elementVente->getElementArrivage()->getQuantiteRestante() ) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', "Vous avez saisie une quantite supérieur à celle du stock!")
                ->addViolation();
          }
        }

      }

    }
}
