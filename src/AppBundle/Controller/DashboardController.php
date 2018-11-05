<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use AppBundle\Service\ElementCaisseService;

class DashboardController extends Controller
{
  /**
   * @Route("/", name="homepage")
   */
  public function indexAction(Request $request, ElementCaisseService $elementCaisseService, \Swift_Mailer $mailer)
  {
      $em = $this->getDoctrine()->getManager();
      // $qb = $em->createQueryBuilder();
      // $qb->select('count(c.id)')
      //    ->from('AppBundle:Client', 'c');
      // $countClient = $qb->getQuery()->getSingleScalarResult();
      //
      // $qb = $em->createQueryBuilder();
      // $qb->select('count(c.id)')
      //    ->from('AppBundle:Fournisseur', 'c');
      // $countFournisseur = $qb->getQuery()->getSingleScalarResult();
      //
      // $qb = $em->createQueryBuilder();
      // $qb->select('count(c.id)')
      //    ->from('AppBundle:Vente', 'c');
      // $countVente = $qb->getQuery()->getSingleScalarResult();
      //
      // $qb = $em->createQueryBuilder();
      // $qb->select('count(c.id)')
      //    ->from('AppBundle:Arrivage', 'c');
      // $countArrivage = $qb->getQuery()->getSingleScalarResult();

      $dateBegin = new \DateTime();
      $dateBegin->modify("-1 year");
      $qb = $em->createQueryBuilder();
      $qb->select('sum(v.montant),  substring(v.date,1,4) as YEAR, substring(v.date,6,2) as MONTH')
         ->from('AppBundle:Vente', 'v')
         ->where('v.date > :dateBegin')
         ->groupBy('YEAR','MONTH')
         ->setParameter('dateBegin',$dateBegin->format("Y-m-d"))
         ;
      $data = $qb->getQuery()->getResult();

      #lineChartDemo
      $values = array();
      $months = array();
      foreach($data as $array){
        $months[] = date("F", strtotime("2001-" . $array['MONTH'] . "-01"));
        $values[] = $array[1] + 0;
      }
      $dateTmp = new \DateTime();
      if ($data){
        $monthTmp = $data[count($data) - 1]['MONTH'];
        $dateTmp->setDate("2001",$monthTmp,"1");
      }

      while( count($months) <12 ){
          $months[] = $dateTmp->modify("+ 1 month")->format('F');
          $values[] = 0;
      }

      #pieChartDemo1
      $previousMonth = new \DateTime("first day of last month");
      $previousMonth->setTime(0,0);
      // die('qq'.$previousMonth->format('Y-m-d H:i'));
      $thisMonth = new \DateTime("first day of this month");
      $thisMonth->setTime(0,0);
      $qb = $em->createQueryBuilder();
      $dataChart1 =
      $em->createQuery("
          SELECT sum(v.montant) as MONTANT, c.name as NAME
          FROM AppBundle:Vente v
          INNER JOIN AppBundle:Client c
          WHERE v.client = c.id
          AND v.date BETWEEN '".$previousMonth->format('Y-m-d H:i')."' AND '".$thisMonth->format('Y-m-d H:i')."'
          GROUP BY NAME
      ")->getResult();
      $dataPieChart1 = array();
      foreach ($dataChart1 as $key => $array){
        $arr = array();
        $arr['value'] = $array['MONTANT'] + 0;
        $arr['label'] = $array['NAME'];
        $arr['color'] = ($key % 3 == 0 ? "lightgreen" : ($key % 2 == 0 ? '#46BFBD' : '#F7464A'));
        $arr['highlight'] = ($key % 3 == 0 ? "yellowgreen" : ($key % 2 == 0 ? '#5AD3D1' : '#FF5A5E'));
        $dataPieChart1[] = $arr;
      }


      #pieChartDemo2
      $thisMonth = new \DateTime("first day of this month");
      $thisMonth->setTime(0,0);
      // die('qq'.$previousMonth->format('Y-m-d H:i'));
      $nextMonth = new \DateTime("first day of next month");
      $nextMonth->setTime(0,0);
      $qb = $em->createQueryBuilder();
      $dataChart2 =
      $em->createQuery("
          SELECT sum(v.montant) as MONTANT, c.name as NAME
          FROM AppBundle:Vente v
          INNER JOIN AppBundle:Client c
          WHERE v.client = c.id
          AND v.date BETWEEN '".$thisMonth->format('Y-m-d H:i')."' AND '".$nextMonth->format('Y-m-d H:i')."'
          GROUP BY NAME
      ")->getResult();

      $dataPieChart2 = array();
      foreach ($dataChart2 as $key => $array){
        $arr = array();
        $arr['value'] = $array['MONTANT'] + 0;
        $arr['label'] = $array['NAME'];
        $arr['color'] = ($key % 3 == 0 ? "lightgreen" : ($key % 2 == 0 ? '#46BFBD' : '#F7464A'));
        $arr['highlight'] = ($key % 3 == 0 ? "yellowgreen" : ($key % 2 == 0 ? '#5AD3D1' : '#FF5A5E'));
        $dataPieChart2[] = $arr;
      }

      # Month Product
      $qb = $em->createQueryBuilder();
      $dataChart3 =
      $em->createQuery("
          SELECT sum(v.montant) as MONTANT, p.name as NAME
          FROM AppBundle:Vente v
          INNER JOIN AppBundle:ElementVente EV
          WHERE v.id = EV.vente
          INNER JOIN AppBundle:ElementArrivage EA
          AND EV.elementArrivage = EA.id
          INNER JOIN AppBundle:Produit p
          AND EA.produit = p.id
          AND v.date BETWEEN '".$thisMonth->format('Y-m-d H:i')."' AND '".$nextMonth->format('Y-m-d H:i')."'
          GROUP BY NAME
      ")->getResult();
      $dataPieChart3 = array();
      foreach ($dataChart3 as $key => $array){
        $arr = array();
        $arr['value'] = $array['MONTANT'] + 0;
        $arr['label'] = $array['NAME'];
        $arr['color'] = ($key % 3 == 0 ? "lightgreen" : ($key % 2 == 0 ? '#46BFBD' : '#F7464A'));
        $arr['highlight'] = ($key % 3 == 0 ? "yellowgreen" : ($key % 2 == 0 ? '#5AD3D1' : '#FF5A5E'));
        $dataPieChart3[] = $arr;
      }

      #Caisse:
      $elementCaisse = $elementCaisseService->obtainElementCaisse();
      $em->persist($elementCaisse);
      $em->flush();

      #Ventes du Jour:
      $ventes = $em->getRepository('AppBundle:Vente')->findTodayVentes();
      $countVente = count($ventes);

      #Arrivages du Jour:
      $arrivages = $em->getRepository('AppBundle:Arrivage')->findTodayArrivages();
      $countArrivage = count($arrivages);

      #Payements du Jour:
      $payements = $em->getRepository('AppBundle:Payement')->findTodayPayements();
      $countPayements = count($payements);


      #Cheques A Regler:
      $cheques = $em->getRepository('AppBundle:Payement')->findChequesNonPayes();

      #Client - Crédits
      $clients = $em->getRepository('AppBundle:Client')->findClientCredit();

      #Monstock
      $monstock = $em->getRepository('AppBundle:ElementArrivage')->monstockIndex();

      #Pertes
      $pertes = $em->getRepository('AppBundle:Perte')->findTodayPertes();

      #Mail
      // $mailBody = $this->renderView('dashboard/dashboard.html.twig', array(
      //     'countArrivage' => $countArrivage,
      //     'countVente' => $countVente,
      //     // 'countFournisseur' => $countFournisseur,
      //     // 'countClient' => $countClient,
      //     'months' => $months,
      //     'values' => $values,
      //     'dataPieChart1' => $dataPieChart1,
      //     'monstock' => $monstock,
      //     'dataPieChart2' => $dataPieChart2,
      //     'dataPieChart3' => $dataPieChart3,
      //     'elementCaisse' => $elementCaisse,
      //     'ventes' => $ventes,
      //     'payements' => $payements,
      //     'clients' => $clients,
      //     'pertes' => $pertes,
      //     'arrivages' => $arrivages,
      //     'cheques' => $cheques,
      //     'countPayements' => $countPayements
      //     ),
      // 'text/html'
      // );
      //
      // $message = (new \Swift_Message('Hello Email'))
      //         ->setFrom('tunisiewebpro@gmail.com')
      //         ->setTo('garalimedkarim@gmail.com')
      //         ->setEncoder( new \Swift_Mime_ContentEncoder_PlainContentEncoder('8bit'))
      //         ->setBody($mailBody)
      //         ->setContentType("text/html");
      //
      // $mailer->send($message);

      return $this->render('dashboard/dashboard.html.twig', array(
          'countArrivage' => $countArrivage,
          'countVente' => $countVente,
          // 'countFournisseur' => $countFournisseur,
          // 'countClient' => $countClient,
          'months' => $months,
          'values' => $values,
          'dataPieChart1' => $dataPieChart1,
          'monstock' => $monstock,
          'dataPieChart2' => $dataPieChart2,
          'dataPieChart3' => $dataPieChart3,
          'elementCaisse' => $elementCaisse,
          'ventes' => $ventes,
          'payements' => $payements,
          'clients' => $clients,
          'pertes' => $pertes,
          'arrivages' => $arrivages,
          'cheques' => $cheques,
          'countPayements' => $countPayements,
      ));

  }
}
