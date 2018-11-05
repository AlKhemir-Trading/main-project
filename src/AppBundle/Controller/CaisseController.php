<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ElementCaisse;
use AppBundle\Entity\ActionCaisse;
use AppBundle\Service\ElementCaisseService;
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
  public function indexAction(ElementCaisseService $elementCaisseService)
  {
      $em = $this->getDoctrine()->getManager();

      $elementCaisse = $elementCaisseService->obtainElementCaisse();
      $em->persist($elementCaisse);
      $em->flush();

      $elementsCaisse = $em->getRepository('AppBundle:ElementCaisse')->findAll();
      //$actionsCaisse = $em->getRepository('AppBundle:ActionCaisse')->findAll();
      $caisse = 0;
      if(count($elementsCaisse > 0)){
        $res = $em->getRepository('AppBundle:ElementCaisse')->findLastElementCaisse();
        $caisse = $res[0]->getFermutureCaisse();
      }
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
