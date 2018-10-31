<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\ElementCaisse;

class ElementCaisseService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function obtainElementCaisse()
    {
      $elementCaisse = $this->em->getRepository('AppBundle:ElementCaisse')->findOneBy(array('date' => new \DateTime()));

      if (!$elementCaisse){
          $elementCaisse = new ElementCaisse();
          $res = $this->em->getRepository('AppBundle:ElementCaisse')->findLastElementCaisse();
          if(count($res)){
           $lastElementCaisse = $res[0];
           $elementCaisse->setOuvertureCaisse($lastElementCaisse->getFermutureCaisse());
           $elementCaisse->setFermutureCaisse($lastElementCaisse->getFermutureCaisse());
          }
      }

      return $elementCaisse;
    }
}

?>
