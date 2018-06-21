<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Perte;
/**
 * [MonstockController description]
 * @Route("monstock")
 */
class MonstockController extends Controller
{
    /**
     * @Route("/", name ="monstock_index")
     */
    public function indexAction()
    {
      $em = $this->getDoctrine()->getManager();

      $monstock = $em->getRepository('AppBundle:ElementArrivage')->monstockIndex();

      $dataPertes = array();
      foreach($monstock as $elementArrivage){
        $pertes = $elementArrivage->getPerdus();
        $newPerte = new Perte();
        $newPerte->setElementArrivage($elementArrivage);
        $newPerteForm = $this->createForm('AppBundle\Form\PerteType', $newPerte);
        $dataPertes[$elementArrivage->getId()]['newForm'] = $newPerteForm->createView();
        // foreach($pertes as $perte){
        //   $deleteForm = $this->createFormBuilder()
        //       ->setAction($this->generateUrl('perte_delete', array('id' => $perte->getId())))
        //       ->setMethod('DELETE')
        //       ->getForm();
        //   $dataPertes[$elementArrivage->getId()]['deleteForm'][$perte->getId()] = $deleteForm->createView();
        // }
      }

      return $this->render('monstock/index.html.twig', array(
        "monstock" => $monstock,
        "dataPertes" => $dataPertes,
      ));
    }

}
