<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Vente;
use AppBundle\Entity\ElementVente;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Vente controller.
 *
 * @Route("vente")
 */
class VenteController extends Controller
{
    /**
     * Lists all vente entities.
     *
     * @Route("/", name="vente_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $ventes = $em->getRepository('AppBundle:Vente')->findAll();

        $em = $this->getDoctrine()->getManager();
        $monstock = $em->getRepository('AppBundle:ElementArrivage')->monstockIndex();

        $countMonstock = count($monstock);
        if ($countMonstock == 0)
          $this->addFlash(
              'warning',
              'Votre stock est entierement vide ..'
          );

        return $this->render('vente/index.html.twig', array(
            'ventes' => $ventes,
            'countMonstock' => $countMonstock,
        ));
    }

    /**
     * Creates a new vente entity.
     *
     * @Route("/new", name="vente_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $vente = new Vente();

        $em = $this->getDoctrine()->getManager();
        $monstock = $em->getRepository('AppBundle:ElementArrivage')->monstockIndex();

        foreach ($monstock as $elementArrivage){
          $eltVente = new ElementVente();
          $eltVente->setElementArrivage($elementArrivage);
          $eltVente->setVente($vente);
          $vente->addElementsVente($eltVente);
        }

        $form = $this->createForm('AppBundle\Form\VenteType', $vente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $elementsVente = $vente->getElementsVente();
            foreach( $elementsVente as $element){
               if ( $element->getQuantite() == 0 || $element->getPrixUnit() == 0 )
                $vente->removeElementsVente($element);
            }

            if (count($elementsVente) == 0 ){
              $this->addFlash(
                  'danger',
                  'Une Vente doit contenir au moins un produit!'
              );
              return $this->redirectToRoute('vente_new');
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($vente);
            $em->flush();

            // MAJ ELEMENT_ARRIVAGE
            foreach ($elementsVente as $elt){
              $id = $elt->getElementArrivage()->getId(); //echo "--".$id;
              $elementArrivage = $em->getRepository('AppBundle:ElementArrivage')->find($id);
              $elementsVenteConcerne = $em->getRepository('AppBundle:ElementVente')->findBy( ['elementArrivage' => $id] );
              $qte_vendu = 0; //print_r($elementsVenteConcerne); die;
              foreach($elementsVenteConcerne as $elt){
                $qte_vendu += $elt->getQuantite();
              }
              $elementArrivage->setQuantiteVendu($qte_vendu);
              //$elementArrivage->setQuantiteRestante($elementArrivage->getQuantite() - $qte_vendu);
            }

            $em->flush();

            return $this->redirectToRoute('vente_show', array('id' => $vente->getId()));
        }

        return $this->render('vente/new.html.twig', array(
            'vente' => $vente,
            'form' => $form->createView(),
            //"monstock" => $monstock,
        ));
    }

    /**
     * Finds and displays a vente entity.
     *
     * @Route("/{id}", name="vente_show")
     * @Method("GET")
     */
    public function showAction(Vente $vente)
    {
        $deleteForm = $this->createDeleteForm($vente);
        $editForm = $this->createForm('AppBundle\Form\VenteType', $vente);

        // foreach ($vente->getElementsVente() as $element)
        //   print_r($element->getQuantite());
        // //die;
        return $this->render('vente/show.html.twig', array(
            'vente' => $vente,
            'delete_form' => $deleteForm->createView(),
            'form' => $editForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing vente entity.
     *
     * @Route("/{id}/edit", name="vente_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Vente $vente)
    {

      $elementVenteInitial = array();
      $elementsArrivageUsed = array();
      foreach ($vente->getElementsVente() as $elt){
         $elementVenteInitial[$elt->getId()] = $elt->getQuantite();
         $elementsArrivageUsed[] = $elt->getElementArrivage()->getId();
      }

      $em = $this->getDoctrine()->getManager();
      $monstock = $em->getRepository('AppBundle:ElementArrivage')->monstockIndex();

      // print_r($vente->getElementsVente()); die;

      $monstock_utilise = array();
      foreach( $vente->getElementsVente() as $elt){
        $monstock_utilise[] = $elt->getElementArrivage()->getId();
      }

      foreach ($monstock as $elementArrivage){
        if ( !in_array($elementArrivage->getId(), $monstock_utilise) ){
          $eltVente = new ElementVente();
          $eltVente->setElementArrivage($elementArrivage);
          $eltVente->setVente($vente);
          $vente->addElementsVente($eltVente);
        }
      }

        $deleteForm = $this->createDeleteForm($vente);
        $editForm = $this->createForm('AppBundle\Form\VenteType', $vente);
        $editForm->handleRequest($request);


        if ($editForm->isSubmitted() && $editForm->isValid()) {

          $elementsVente = $vente->getElementsVente();
          foreach( $elementsVente as $element){
            if( $element->getQuantite() > 0 && $element->getPrixUnit() > 0
              && !in_array( $element->getElementArrivage()->getId(), $elementsArrivageUsed ) ){
              $elementsArrivageUsed[] = $element->getElementArrivage()->getId();
              // $qte_vendu = $element->getElementArrivage()->getQuantiteVendu();
              // $element->getElementArrivage()->setQuantiteVendu( $qte_vendu + ($element->getQuantite() - $elementVenteInitial[$element->getId()]) );
            }
            if ( $element->getQuantite() == 0 || $element->getPrixUnit() == 0 ){
              $vente->removeElementsVente($element);
            }

          }

          // MAJ ELEMENT_ARRIVAGE
          foreach ($elementsArrivageUsed as $id){
            $elementArrivage = $em->getRepository('AppBundle:ElementArrivage')->find($id);
            $elementsVenteConcerne = $em->getRepository('AppBundle:ElementVente')->findBy( ['elementArrivage' => $id] );
            $qte_vendu = 0;
            foreach($elementsVenteConcerne as $elt){
              $qte_vendu += $elt->getQuantite();
            }
            $elementArrivage->setQuantiteVendu($qte_vendu);
            //$elementArrivage->setQuantiteRestante($elementArrivage->getQuantite() - $qte_vendu);
          }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('vente_edit', array('id' => $vente->getId()));
        }

        return $this->render('vente/edit.html.twig', array(
            'vente' => $vente,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a vente entity.
     *
     * @Route("/{id}", name="vente_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Vente $vente)
    {


        $form = $this->createDeleteForm($vente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($vente);
            $em->flush();

            $elementsVente = $vente->getElementsVente();
            // MAJ ELEMENT_ARRIVAGE
            foreach ($elementsVente as $elt){
              $id = $elt->getElementArrivage()->getId(); //echo "--".$id;
              $elementArrivage = $em->getRepository('AppBundle:ElementArrivage')->find($id);
              $elementsVenteConcerne = $em->getRepository('AppBundle:ElementVente')->findBy( ['elementArrivage' => $id] );
              $qte_vendu = 0; //print_r($elementsVenteConcerne); die;
              foreach($elementsVenteConcerne as $elt){
                $qte_vendu += $elt->getQuantite();
              }
              $elementArrivage->setQuantiteVendu($qte_vendu);
              //$elementArrivage->setQuantiteRestante($elementArrivage->getQuantite() - $qte_vendu);
            }

            $em->flush();

        }

        return $this->redirectToRoute('vente_index');
    }

    /**
     * Creates a form to delete a vente entity.
     *
     * @param Vente $vente The vente entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Vente $vente)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('vente_delete', array('id' => $vente->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
