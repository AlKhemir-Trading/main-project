<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\Payement;
use Symfony\Component\HttpFoundation\Request;

/**
 * Suivi Cheque controller.
 *
 * @Route("suivi-cheque")
 */
class SuiviChequeController extends Controller
{
    /**
     * @Route("/", name="suivicheque_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $cheques = $em->getRepository('AppBundle:Payement')->findBy(array('type'=>'cheque'));

        return $this->render('suivicheque/index.html.twig', array(
            'cheques' => $cheques
        ));
    }

    /**
     * Displays a form to edit an existing cheque
     *
     * @Route("/cheque/{id}/edit", name="cheque_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Payement $payement)
    {
        $editForm = $this->createForm('AppBundle\Form\ChequeType', $payement);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                'Un Cheque a Changé de status en "Payé"'
            );

            return $this->redirectToRoute('suivicheque_index');
        }

        return $this->render('suivicheque/editCheque.html.twig', array(
            'cheque' => $payement,
            'form' => $editForm->createView(),
        ));
    }


}
