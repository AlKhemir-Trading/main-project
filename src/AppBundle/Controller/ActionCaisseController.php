<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ActionCaisse;
use AppBundle\Entity\ElementCaisse;
use AppBundle\Service\ElementCaisseService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Actioncaisse controller.
 *
 * @Route("actioncaisse")
 */
class ActionCaisseController extends Controller
{
    /**
     * Creates a new actionCaisse entity.
     *
     * @Route("/new", name="actioncaisse_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, ElementCaisseService $elementCaisseService)
    {
        $actionCaisse = new ActionCaisse();
        $actionCaisse->setUser($this->getUser());
        $form = $this->createForm('AppBundle\Form\ActionCaisseType', $actionCaisse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($actionCaisse->getType() == "retrait")
                $actionCaisse->setMontant(-$actionCaisse->getMontant());

            $em = $this->getDoctrine()->getManager();

            $elementCaisse = $elementCaisseService->obtainElementCaisse();

            $actionCaisse->setElementCaisse($elementCaisse);
            $elementCaisse->addActionsCaisse($actionCaisse);

            $em->persist($elementCaisse);
            $em->persist($actionCaisse);
            $em->flush();

            $this->addFlash(
                'success',
                'action caisse "'.$actionCaisse->getMotif().'" ajoutée avec succes.'
            );

            // return $this->redirectToRoute('actioncaisse_show', array('id' => $actionCaisse->getId()));
            return $this->redirectToRoute('caisse_index');
        }

        $this->addFlash(
            'danger',
            'Erreur de Saisie de Transaction Caisse. Veuillez recommencer.'
        );

        return $this->redirectToRoute('caisse_index');
    }

    /**
     * Deletes a actionCaisse entity.
     *
     * @Route("/{id}", name="actioncaisse_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ActionCaisse $actionCaisse)
    {
        $form = $this->createDeleteForm($actionCaisse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if( $actionCaisse->getType() != 'client' && $actionCaisse->getDate()->diff(new \DateTime())->format('%a') === '0' ){
                $em = $this->getDoctrine()->getManager();
                $actionCaisse->getElementCaisse()->removeActionsCaisse($actionCaisse);
                $em->remove($actionCaisse);
                $em->flush();

                $this->addFlash(
                    'danger',
                    'action caisse "'.$actionCaisse->getMotif().'" supprimée avec succes.'
                );
            }else{
                // throw new \Exception('Vous n\'avez pas le droit de supprimer cette action caisse');
                throw $this->createNotFoundException('Vous n\'avez pas le droit de supprimer cette action caisse');
            }
        }

        return $this->redirectToRoute('caisse_index');
    }

    // /**
    //  * Lists all actionCaisse entities.
    //  *
    //  * @Route("/", name="actioncaisse_index")
    //  * @Method("GET")
    //  */
    // public function indexAction()
    // {
    //     $em = $this->getDoctrine()->getManager();
    //
    //     $actionCaisses = $em->getRepository('AppBundle:ActionCaisse')->findAll();
    //
    //     return $this->render('actioncaisse/index.html.twig', array(
    //         'actionCaisses' => $actionCaisses,
    //     ));
    // }
    //
    // /**
    //  * Finds and displays a actionCaisse entity.
    //  *
    //  * @Route("/{id}", name="actioncaisse_show")
    //  * @Method("GET")
    //  */
    // public function showAction(ActionCaisse $actionCaisse)
    // {
    //     $deleteForm = $this->createDeleteForm($actionCaisse);
    //
    //     return $this->render('actioncaisse/show.html.twig', array(
    //         'actionCaisse' => $actionCaisse,
    //         'delete_form' => $deleteForm->createView(),
    //     ));
    // }
    //
    // /**
    //  * Displays a form to edit an existing actionCaisse entity.
    //  *
    //  * @Route("/{id}/edit", name="actioncaisse_edit")
    //  * @Method({"GET", "POST"})
    //  */
    // public function editAction(Request $request, ActionCaisse $actionCaisse)
    // {
    //     $deleteForm = $this->createDeleteForm($actionCaisse);
    //     $editForm = $this->createForm('AppBundle\Form\ActionCaisseType', $actionCaisse);
    //     $editForm->handleRequest($request);
    //
    //     if ($editForm->isSubmitted() && $editForm->isValid()) {
    //         $this->getDoctrine()->getManager()->flush();
    //
    //         return $this->redirectToRoute('actioncaisse_edit', array('id' => $actionCaisse->getId()));
    //     }
    //
    //     return $this->render('actioncaisse/edit.html.twig', array(
    //         'actionCaisse' => $actionCaisse,
    //         'edit_form' => $editForm->createView(),
    //         'delete_form' => $deleteForm->createView(),
    //     ));
    // }

    /**
     * Creates a form to delete a actionCaisse entity.
     *
     * @param ActionCaisse $actionCaisse The actionCaisse entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ActionCaisse $actionCaisse)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('actioncaisse_delete', array('id' => $actionCaisse->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    // public function obtainElementCaisse(){
    //     $em = $this->getDoctrine()->getManager();
    //     $elementCaisse = $em->getRepository('AppBundle:ElementCaisse')->findOneBy(array('date' => new \DateTime()));
    //
    //     if (!$elementCaisse){
    //         $elementCaisse = new ElementCaisse();
    //         $em = $this->getDoctrine()->getManager();
    //         $res = $em->getRepository('AppBundle:ElementCaisse')->findLastElementCaisse();
    //         if(count($res)){
    //          $lastElementCaisse = $res[0];
    //          $elementCaisse->setOuvertureCaisse($lastElementCaisse->getFermutureCaisse());
    //         }
    //     }
    //
    //     return $elementCaisse;
    // }
}
