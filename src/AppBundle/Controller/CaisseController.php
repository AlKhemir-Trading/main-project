<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ElementCaisse;
use AppBundle\Entity\ActionCaisse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Caisse Controller.
 *
 * @Route("caisse")
 */
class CaisseController extends Controller
{

  /**
   * Lists all elementCaisse entities.
   *
   * @Route("/", name="caisse_index")
   * @Method("GET")
   */
  public function indexAction()
  {
      $em = $this->getDoctrine()->getManager();

      $elementsCaisse = $em->getRepository('AppBundle:ElementCaisse')->findAll();
      //$actionsCaisse = $em->getRepository('AppBundle:ActionCaisse')->findAll();
      $caisse = ($em->getRepository('AppBundle:ElementCaisse')->findLastElementCaisse())[0]->getFermutureCaisse();

      #Form ActionCaisse:
      $actionCaisse = new ActionCaisse();
      $actionCaisse->setUser($this->getUser());
      $formActionCaisse = $this->createForm('AppBundle\Form\ActionCaisseType', $actionCaisse);

      return $this->render('elementcaisse/index.html.twig', array(
          'elementsCaisse'=>$elementsCaisse,
          'formActionCaisse' => $formActionCaisse->createView(),
          'caisse' => $caisse,
      ));
  }


  /**
   * Lists all elementCaisse entities.
   *
   * @Route("/edit", name="caisse_edit")
   * @Method("GET")
   */
  public function editAction()
  {
    $em = $this->getDoctrine()->getManager();

    $elementsCaisse = $em->getRepository('AppBundle:ElementCaisse')->findAll();
    //$actionsCaisse = $em->getRepository('AppBundle:ActionCaisse')->findAll();

    #Form ActionCaisse:
    $actionCaisse = new ActionCaisse();
    $actionCaisse->setUser($this->getUser());
    $formActionCaisse = $this->createForm('AppBundle\Form\ActionCaisseType', $actionCaisse);

    #ActionCaisse Delete Form
    $actionCaisseDeleteForm = $this->createFormBuilder()
        ->setAction($this->generateUrl('actioncaisse_delete', array('id' => 'id' )))
        ->setMethod('DELETE')
        ->getForm()
    ;

    return $this->render('elementcaisse/edit.html.twig', array(
        // 'elementCaisses' => $elementCaisses,
        //'actionsCaisse' => $actionsCaisse,
        'elementsCaisse'=>$elementsCaisse,
        'formActionCaisse' => $formActionCaisse->createView(),
        'actionCaisseDeleteForm' => $actionCaisseDeleteForm->createView(),
    ));
  }

}
