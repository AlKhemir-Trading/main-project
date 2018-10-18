<?php

namespace AppBundle\Repository;

/**
 * VenteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VenteRepository extends \Doctrine\ORM\EntityRepository
{

  public function venteByClientId($id)
  {
      // return $this->getEntityManager()
      //     ->createQuery('
      //         SELECT p. FROM AppBundle:Payement p
      //         where p.client = '.$id.'
      //     ')
      //     //ORDER BY p.quantite ASC
      //     ->getResult();

      return  $this->createQueryBuilder('v')
                    ->andWhere('v.client = :id')
                    ->setParameter('id', $id)
                    ->select('SUM(v.montant) as Total')
                    ->getQuery()
                    ->getOneOrNullResult();
  }

  public function ventesNonPayes($id)
  {
      return $this->getEntityManager()
          ->createQuery('
              SELECT v FROM AppBundle:Vente v
              where v.montantPaye < v.montant
              AND v.client = '.$id.'
              ORDER BY v.date ASC
          ')
          ->getResult();
  }

  // public function ventesNonPayesUntilPreparation($id,$date)
  // {
  //     return $this->getEntityManager()
  //         ->createQuery('
  //             SELECT SUM(v.montant - v.montantPaye)
  //             FROM AppBundle:Vente v
  //             where v.montantPaye < v.montant
  //             AND v.client = '.$id.'
  //             AND v.date > :date
  //             ORDER BY v.date ASC
  //         ')
  //         ->setParameter('date',$date,\Doctrine\DBAL\Types\Type::DATETIME)
  //         ->getResult();
  // }

  public function ventesNonPayesUntil($idClient,$limit,$idVente)
    { //$date = new \DateTime();
      //die($date->format('Y-m-d H:i:s'));
      return $this->getEntityManager()
          ->createQuery("
              SELECT v FROM AppBundle:Vente v
              where v.montantPaye < v.montant
              AND v.client = ".$idClient."
              AND v.id != ".$idVente."
              ORDER BY v.date ASC
          ")
          //  AND v.date > '".$date."'
          ->setMaxResults($limit)
          ->getResult();
  }

  public function ventesPayesUntil($idClient,$limit,$date)
  {
      return $this->getEntityManager()
          ->createQuery("
              SELECT v FROM AppBundle:Vente v
              where v.montantPaye > 0
              AND v.client = ".$idClient."
              AND v.date > '".$date."'
              ORDER BY v.date DESC
          ")
          //AND v.id != ".$idVente."
          ->setMaxResults($limit)
          ->getResult();
  }

  public function getLastVenteMontantPayeNotZero($idClient){
      return $this->getEntityManager()
          ->createQuery("
              SELECT v FROM AppBundle:Vente v
              where v.montantPaye > 0
              AND v.client = ".$idClient."
              ORDER BY v.date DESC
          ")
          //AND v.date > '".$date."'
          ->setMaxResults(1)
          ->getResult();
  }

  public function firstVenteUmpayed($idClient){
      return $this->getEntityManager()
          ->createQuery("
              SELECT v FROM AppBundle:Vente v
              where v.montantPaye < v.montant
              AND v.client = ".$idClient."
              ORDER BY v.date ASC
          ")
          //AND v.date > '".$date."'
          ->setMaxResults(1)
          ->getResult();
  }

  public function getVentesToTreat($idClient,$date1,$date2){
    return $this->getEntityManager()
        ->createQuery("
            SELECT v FROM AppBundle:Vente v
            WHERE v.client = ".$idClient."
            AND v.date BETWEEN '".$date1."' AND '".$date2."'
            ORDER BY v.date DESC
        ")
        //AND v.date > '".$date."'
        //->setMaxResults($limit)
        ->getResult();
  }

  public function montantOriginal($id)
  {
      return $this->getEntityManager()
          ->createQuery('
              SELECT v.montant
              FROM AppBundle:Vente v
              where v.id = '.$id.'
          ')
          ->getResult();
  }

  public function getPayementVentes($id,$limit)
  {
      return $this->getEntityManager()
          ->createQuery('
              SELECT v FROM AppBundle:Vente v
              where v.montantPaye > 0
              AND v.client = '.$id.'
              ORDER BY v.date DESC
          ')
          ->setMaxResults($limit)
          ->getResult();
  }

  public function SumMontantLastVentes($limitRequest,$clientId){
    // return $this->createQueryBuilder('v')
    //   ->andWhere('v.client = :client')
    //   ->andWhere('v.montantPaye > 0')
    //   ->setParameter('client', $clientId)
    //   ->setMaxResults($limitRequest)
    //   ->select('SUM(v.montantPaye) as Total')
    //   ->orderBy('v.date', 'ASC')
    //   //->setFirstResult(0)
    //   ->getQuery()
    //   ->getResult();

    // return $this->createQueryBuilder('v')
    //   ->andWhere('v.client = :client')
    //   ->andWhere('v.montantPaye > 0')
    //   ->setParameter('client', $clientId)
    //   ->setMaxResults($limitRequest)
    //   ->select('v.montantPaye')
    //   ->orderBy('v.date', 'ASC')
    //   ->getQuery();
        //->setFirstResult(0)
        //->getDQL();

    // return $this->createQueryBuilder('u')
    //         ->select('SUM(v.montantPaye) as Total')
    //         ->from($subquery,'v')
    //         ->getQuery()
            // ->getOneOrNullResult();

    return $this->getEntityManager()
      ->createQuery('
          SELECT SUM(montantPaye) as Total
          FROM
            SELECT v.montantPaye
            FROM AppBundle:Vente v
            where v.montantPaye > 0
            AND v.client = '.$clientId.'
            ORDER BY v.date ASC
            limit '.$limitRequest.'
      ')
      ->getResult();
  }

}
