<?php

namespace AppBundle\Repository;

/**
 * ElementArrivageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ElementArrivageRepository extends \Doctrine\ORM\EntityRepository
{
  public function monstockIndex()
  {
      return $this->getEntityManager()
          ->createQuery('
              SELECT p FROM AppBundle:ElementArrivage p
              where p.quantiteRestante > 0
          ')
          //ORDER BY p.quantite ASC
          ->getResult();
  }
}
