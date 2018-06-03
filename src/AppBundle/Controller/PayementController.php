<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Payement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Payement controller.
 *
 * @Route("payement")
 */
class PayementController extends Controller
{
    /**
     * Lists all payement entities.
     *
     * @Route("/", name="payement_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $payements = $em->getRepository('AppBundle:Payement')->findAll();

        return $this->render('payement/index.html.twig', array(
            'payements' => $payements,
        ));
    }

    /**
     * Creates a new payement entity.
     *
     * @Route("/new", name="payement_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $payement = new Payement();
        $form = $this->createForm('AppBundle\Form\PayementType', $payement);
        $form->handleRequest($request);
        //die('qqaaa'.$payement->getClient()->getName());
        // die('qqq'.$payement->getMontant());
        // die("ss".$form->get('client ')->getData());
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($payement);
            $em->flush();

            return $this->redirectToRoute('payement_show', array('id' => $payement->getId()));
        }

        return $this->render('payement/new.html.twig', array(
            'payement' => $payement,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a payement entity.
     *
     * @Route("/{id}", name="payement_show")
     * @Method("GET")
     */
    public function showAction(Payement $payement)
    {
        $deleteForm = $this->createDeleteForm($payement);

        return $this->render('payement/show.html.twig', array(
            'payement' => $payement,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing payement entity.
     *
     * @Route("/{id}/edit", name="payement_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Payement $payement)
    {
        $deleteForm = $this->createDeleteForm($payement);
        $editForm = $this->createForm('AppBundle\Form\PayementType', $payement);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('payement_edit', array('id' => $payement->getId()));
        }

        return $this->render('payement/edit.html.twig', array(
            'payement' => $payement,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a payement entity.
     *
     * @Route("/{id}", name="payement_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Payement $payement)
    {
        $form = $this->createDeleteForm($payement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($payement);
            $em->flush();
        }

        return $this->redirectToRoute('payement_index');
    }

    /**
     * Creates a form to delete a payement entity.
     *
     * @param Payement $payement The payement entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Payement $payement)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('payement_delete', array('id' => $payement->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
