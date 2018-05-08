<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Arrivage;
use AppBundle\Entity\ElementArrivage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Arrivage controller.
 *
 * @Route("arrivage")
 */
class ArrivageController extends Controller
{
    /**
     * Lists all arrivage entities.
     *
     * @Route("/", name="arrivage_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $arrivages = $em->getRepository('AppBundle:Arrivage')->findAll();

        return $this->render('arrivage/index.html.twig', array(
            'arrivages' => $arrivages,
        ));
    }

    /**
     * Creates a new arrivage entity.
     *
     * @Route("/new", name="arrivage_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $arrivage = new Arrivage();
        // $elementArrivage = new ElementArrivage();
        // $arrivage->addElementArrivage($elementArrivage);
        // $elementArrivage2 = new ElementArrivage();
        // $arrivage->addElementArrivage($elementArrivage2);

        $form = $this->createForm('AppBundle\Form\ArrivageType', $arrivage);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            //print_r($arrivage->getElementArrivages()[0]->getArrivage()->getId()); die;

            $elementArrivages = $arrivage->getElementArrivages();
            foreach( $elementArrivages as $elementArrivage){
              // print_r($elementArrivage->getQuantite());
              $elementArrivage->setArrivage($arrivage);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($arrivage);
            $em->flush();

            return $this->redirectToRoute('arrivage_show', array('id' => $arrivage->getId()));
        }

        return $this->render('arrivage/new.html.twig', array(
            'arrivage' => $arrivage,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a arrivage entity.
     *
     * @Route("/{id}", name="arrivage_show")
     * @Method("GET")
     */
    public function showAction(Arrivage $arrivage)
    {
        $deleteForm = $this->createDeleteForm($arrivage);
        $edit_form = $this->createForm('AppBundle\Form\ArrivageType', $arrivage);

        return $this->render('arrivage/show.html.twig', array(
            'arrivage' => $arrivage,
            'form' => $edit_form->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing arrivage entity.
     *
     * @Route("/{id}/edit", name="arrivage_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Arrivage $arrivage)
    {
        $deleteForm = $this->createDeleteForm($arrivage);
        $editForm = $this->createForm('AppBundle\Form\ArrivageType', $arrivage);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $elementArrivages = $arrivage->getElementArrivages();
            foreach( $elementArrivages as $elementArrivage){
              // print_r($elementArrivage->getQuantite());
              $elementArrivage->setArrivage($arrivage);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('arrivage_edit', array('id' => $arrivage->getId()));
        }

        return $this->render('arrivage/edit.html.twig', array(
            'arrivage' => $arrivage,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a arrivage entity.
     *
     * @Route("/{id}", name="arrivage_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Arrivage $arrivage)
    {
        $form = $this->createDeleteForm($arrivage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($arrivage);
            $em->flush();
        }

        return $this->redirectToRoute('arrivage_index');
    }

    /**
     * Creates a form to delete a arrivage entity.
     *
     * @param Arrivage $arrivage The arrivage entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Arrivage $arrivage)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('arrivage_delete', array('id' => $arrivage->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
