<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Perte;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Perte controller.
 *
 * @Route("perte")
 */
class PerteController extends Controller
{
    /**
     * Lists all perte entities.
     *
     * @Route("/", name="perte_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $pertes = $em->getRepository('AppBundle:Perte')->findAll();

        return $this->render('perte/index.html.twig', array(
            'pertes' => $pertes,
        ));
    }

    /**
     * Creates a new perte entity.
     *
     * @Route("/new", name="perte_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // $data = $request->request->all();
        // $elementArrivageId = $data['appbundle_perte']['elementArrivage'];
        // $elementArrivage = $em->find('AppBundle\Entity\ElementArrivage', $elementArrivageId);

        $perte = new Perte();
        $form = $this->createForm('AppBundle\Form\PerteType', $perte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $perte->getElementArrivage()->addPerdus($perte);
            $perte->getElementArrivage()->updateTotalPerdu();
            $em->persist($perte);
            $em->flush();

            $this->addFlash(
                'success',
                'Perte Déclarée avec Succéss: '.$perte->getPerdu().' de '.$perte->getElementArrivage()->getProduit()->getName()
            );

            return $this->redirectToRoute('monstock_index', array());
        }

        return $this->render('perte/new.html.twig', array(
            'perte' => $perte,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a perte entity.
     *
     * @Route("/{id}", name="perte_show")
     * @Method("GET")
     */
    public function showAction(Perte $perte)
    {
        $deleteForm = $this->createDeleteForm($perte);

        return $this->render('perte/show.html.twig', array(
            'perte' => $perte,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing perte entity.
     *
     * @Route("/{id}/edit", name="perte_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Perte $perte)
    {
        $deleteForm = $this->createDeleteForm($perte);
        $editForm = $this->createForm('AppBundle\Form\PerteType', $perte);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('perte_edit', array('id' => $perte->getId()));
        }

        return $this->render('perte/edit.html.twig', array(
            'perte' => $perte,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a perte entity.
     *
     * @Route("/{id}", name="perte_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Perte $perte)
    {
        $form = $this->createDeleteForm($perte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $perte->getElementArrivage()->removePerdus($perte);
            $perte->getElementArrivage()->updateTotalPerdu();
            $em->remove($perte);
            $em->flush();

            $this->addFlash(
                'danger',
                'Perte Supprimée avec Succéss: '.$perte->getPerdu().' de '.$perte->getElementArrivage()->getProduit()->getName()
            );

        }

        return $this->redirectToRoute('monstock_index');
    }

    /**
     * Creates a form to delete a perte entity.
     *
     * @param Perte $perte The perte entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Perte $perte)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('perte_delete', array('id' => $perte->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
