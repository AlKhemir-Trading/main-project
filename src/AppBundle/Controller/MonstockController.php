<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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

      return $this->render('monstock/index.html.twig', array(
        "monstock" => $monstock
      ));
    }

}
