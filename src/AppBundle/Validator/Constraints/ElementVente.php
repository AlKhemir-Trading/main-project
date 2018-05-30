<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class ElementVente extends Constraint
{
    public $message = "{{ string }}";
}
