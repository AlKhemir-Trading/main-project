<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Payement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\ActionCaisse;
use AppBundle\Entity\ElementCaisse;
use AppBundle\Service\ElementCaisseService;

/**
 * Payement controller.
 *
 * @Route("payement")
 */
class PayementController extends Controller
{
    // /**
    //  * Lists all payement entities.
    //  *
    //  * @Route("/", name="payement_index")
    //  * @Method("GET")
    //  */
    // public function indexAction()
    // {
    //     $em = $this->getDoctrine()->getManager();
    //
    //     $payements = $em->getRepository('AppBundle:Payement')->findAll();
    //
    //     return $this->render('payement/index.html.twig', array(
    //         'payements' => $payements,
    //     ));
    // }

    /**
     * Creates a new payement entity.
     *
     * @Route("/new", name="payement_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, ElementCaisseService $elementCaisseService)
    {
        $payement = new Payement();
        $form = $this->createForm('AppBundle\Form\PayementType', $payement);
        $form->handleRequest($request);
        //die('qqaaa'.$payement->getClient()->getName());
        // die('qqq'.$payement->getMontant());
        // die("ss".$form->get('client ')->getData());
        if ($form->isSubmitted() && $form->isValid()) {
            $payement->getClient()->addPayement($payement);
            $em = $this->getDoctrine()->getManager();
            $ventesNonPayes = $em->getRepository('AppBundle:Vente')->ventesNonPayes($payement->getClient()->getId());

            $montantPayement = $payement->getMontant();
            foreach ($ventesNonPayes as $vente) {
              if ( $montantPayement == 0)
                break;
              // $montantVente = $vente->getMontant();
              // $montantPayeVente = $vente->getMontantPaye();
              $montantVenteRestant = $vente->getMontant() - $vente->getMontantPaye();
              if ($montantVenteRestant <= $montantPayement ){
                $vente->setMontantPaye($vente->getMontant());
                $montantPayement -= $montantVenteRestant;
              }else{
                $nouveauMontantPaye = $vente->getMontantPaye() + $montantPayement;
                $montantPayement = 0;
                $vente->setMontantPaye($nouveauMontantPaye);
              }
              //echo $vente->getId()."/".$vente->getDate()->format('Y-m-d H:i:s')."<br />";
              $em->persist($vente);
            }
            $payement->getClient()->updatePlusOuMoins();
            $payement->setUser($this->getUser());

            #ActionCaisse
            if ($payement->getType() == "cash"){
              $actionCaisse = new ActionCaisse();
              $actionCaisse->setUser($this->getUser());
              $actionCaisse->setMontant($payement->getMontant());
              $actionCaisse->setType("client");
              $actionCaisse->setMotif("Payement du Client ".$payement->getClient()->getName());
              $actionCaisse->setPayement($payement);
              $payement->setActionCaisse($actionCaisse);

              $elementCaisse = $elementCaisseService->obtainElementCaisse();
              $actionCaisse->setElementCaisse($elementCaisse);
              $elementCaisse->addActionsCaisse($actionCaisse);

              $em->persist($actionCaisse);
              $em->persist($elementCaisse);
            }
            $em->persist($payement);
            $em->flush();

            // return $this->redirectToRoute('payement_show', array('id' => $payement->getId()));
            return $this->redirectToRoute('client_show', array('id' => $payement->getClient()->getId()));
        }

        $this->addFlash(
            'danger',
            'Erreur de Saisie de Payement: Payement non effectué Veuillez recommencer.'
        );
        return $this->redirectToRoute('client_show', array('id' => $payement->getClient()->getId()));
    }

    // /**
    //  * Finds and displays a payement entity.
    //  *
    //  * @Route("/{id}", name="payement_show")
    //  * @Method("GET")
    //  */
    // public function showAction(Payement $payement)
    // {
    //     $deleteForm = $this->createDeleteForm($payement);
    //
    //     return $this->render('payement/show.html.twig', array(
    //         'payement' => $payement,
    //         'delete_form' => $deleteForm->createView(),
    //     ));
    // }
    //
    // /**
    //  * Displays a form to edit an existing payement entity.
    //  *
    //  * @Route("/{id}/edit", name="payement_edit")
    //  * @Method({"GET", "POST"})
    //  */
    // public function editAction(Request $request, Payement $payement)
    // {
    //     $deleteForm = $this->createDeleteForm($payement);
    //     $editForm = $this->createForm('AppBundle\Form\PayementType', $payement);
    //     $editForm->handleRequest($request);
    //
    //     if ($editForm->isSubmitted() && $editForm->isValid()) {
    //         $this->getDoctrine()->getManager()->flush();
    //
    //         return $this->redirectToRoute('payement_edit', array('id' => $payement->getId()));
    //     }
    //
    //     return $this->render('payement/edit.html.twig', array(
    //         'payement' => $payement,
    //         'edit_form' => $editForm->createView(),
    //         'delete_form' => $deleteForm->createView(),
    //     ));
    // }

    /**
     * Deletes a payement entity.
     *
     * @Route("/{id}", name="payement_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Payement $payement, ElementCaisseService $elementCaisseService)
    {
        $form = $this->createDeleteForm($payement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $payement->getClient()->removePayement($payement);
            $montantPayement = $payement->getMontant();

            if($payement->getClient()->getPlusOuMoins() > 0){
              $montantPayement-= $payement->getClient()->getPlusOuMoins();
            }

            if( $montantPayement > 0 ){
              $venteRepository = $em->getRepository('AppBundle:Vente');

              $sumMontantRequest = 0;
              $limitRequest = 0;
              $ventesPayement = array();
              while($sumMontantRequest < $montantPayement){
                $limitRequest += 1;
                $ventesPayement = $venteRepository->getPayementVentes($payement->getClient()->getId(),$limitRequest);
                $sumMontantRequest = 0;
                foreach ($ventesPayement as $vente){
                  $sumMontantRequest += $vente->getMontantPaye();
                }
                // echo $sumMontantRequest .count($ventesPayement). "<br />";
              }

              foreach($ventesPayement as $vente){
                if ($montantPayement >= $vente->getMontantPaye() ){
                  $montantPayement -= $vente->getMontantPaye();
                  $vente->setMontantPaye(0);
                }else{
                  $vente->setMontantPaye($vente->getMontantPaye()-$montantPayement);
                  $montantPayement = 0;
                }
                // $em->persist($vente);
              }
            }
            $payement->getClient()->updatePlusOuMoins();

            #ActionCaisse
            $actionCaisse = new ActionCaisse();
            $actionCaisse->setUser($this->getUser());
            $actionCaisse->setMontant(-$payement->getMontant());
            $actionCaisse->setType("client");
            $actionCaisse->setMotif("Suppression du Payement de ".$payement->getClient()->getName()." du ".$payement->getDate()->format('Y-m-d'));
            $actionCaisse->setPayement(null);

            $elementCaisse = $elementCaisseService->obtainElementCaisse();
            $actionCaisse->setElementCaisse($elementCaisse);
            $elementCaisse->addActionsCaisse($actionCaisse);

            $em->persist($actionCaisse);
            $em->persist($elementCaisse);

            $em->remove($payement);
            $em->flush();
        }

        return $this->redirectToRoute('client_show', array('id' => $payement->getClient()->getId()));
        // return $this->redirectToRoute('payement_index');
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
