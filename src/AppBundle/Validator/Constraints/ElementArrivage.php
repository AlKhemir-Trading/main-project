<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class ElementArrivage extends Constraint
{
    public $message = "{{ string }}";
}
