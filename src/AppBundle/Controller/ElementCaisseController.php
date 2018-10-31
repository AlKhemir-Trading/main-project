<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ElementCaisse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Elementcaisse controller.
 *
 * @Route("elementcaisse")
 */
class ElementCaisseController extends Controller
{
    /**
     * Lists all elementCaisse entities.
     *
     * @Route("/", name="elementcaisse_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $elementCaisses = $em->getRepository('AppBundle:ElementCaisse')->findAll();

        return $this->render('elementcaisse/index.html.twig', array(
            'elementCaisses' => $elementCaisses,
        ));
    }

    /**
     * Creates a new elementCaisse entity.
     *
     * @Route("/new", name="elementcaisse_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $elementCaisse = new Elementcaisse();
        $form = $this->createForm('AppBundle\Form\ElementCaisseType', $elementCaisse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($elementCaisse);
            $em->flush();

            return $this->redirectToRoute('elementcaisse_show', array('id' => $elementCaisse->getId()));
        }

        return $this->render('elementcaisse/new.html.twig', array(
            'elementCaisse' => $elementCaisse,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a elementCaisse entity.
     *
     * @Route("/{id}", name="elementcaisse_show")
     * @Method("GET")
     */
    public function showAction(ElementCaisse $elementCaisse)
    {
        $deleteForm = $this->createDeleteForm($elementCaisse);

        return $this->render('elementcaisse/show.html.twig', array(
            'elementCaisse' => $elementCaisse,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing elementCaisse entity.
     *
     * @Route("/{id}/edit", name="elementcaisse_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ElementCaisse $elementCaisse)
    {
        $deleteForm = $this->createDeleteForm($elementCaisse);
        $editForm = $this->createForm('AppBundle\Form\ElementCaisseType', $elementCaisse);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('elementcaisse_edit', array('id' => $elementCaisse->getId()));
        }

        return $this->render('elementcaisse/edit.html.twig', array(
            'elementCaisse' => $elementCaisse,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a elementCaisse entity.
     *
     * @Route("/{id}", name="elementcaisse_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ElementCaisse $elementCaisse)
    {
        $form = $this->createDeleteForm($elementCaisse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($elementCaisse);
            $em->flush();
        }

        return $this->redirectToRoute('elementcaisse_index');
    }

    /**
     * Creates a form to delete a elementCaisse entity.
     *
     * @param ElementCaisse $elementCaisse The elementCaisse entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ElementCaisse $elementCaisse)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('elementcaisse_delete', array('id' => $elementCaisse->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
