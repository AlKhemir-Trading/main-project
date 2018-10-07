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

        // $data =$form->getData()->getElementArrivages();
        // print_r($data[0]->getMontant()); die('aaa');

        if ($form->isSubmitted() && $form->isValid()) {
            //print_r($arrivage->getElementArrivages()[0]->getArrivage()->getId()); die;
            // print_r($arrivage->getFournisseur()->getName()); die('aaa');
            $elementArrivages = $arrivage->getElementArrivages();
            foreach( $elementArrivages as $elementArrivage){
               //print_r($elementArrivage->getMontant());
              $elementArrivage->setArrivage($arrivage);
            }
            //die;
            // $dateNow = new \DateTime();
            // $arrivage->setDateCreation($dateNow);

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
      // die('aaaaaa');
        $originalElementArrivage = array();
        $backup = array();
        foreach ($arrivage->getElementArrivages() as $elementArrivage) {
          // echo"<br />".$elementArrivage ->getQuantite() ."/". $elementArrivage->getQuantiteRestante()."<br />";
          $originalElementArrivage[$elementArrivage->getId()] = $elementArrivage ->getQuantite() - $elementArrivage->getQuantiteRestante();
          $backup[$elementArrivage->getId()] = $elementArrivage;
        }

        // die('count'.count($elementArrivages = $arrivage->getElementArrivages()) );
        $deleteForm = $this->createDeleteForm($arrivage);
        $editForm = $this->createForm('AppBundle\Form\ArrivageType', $arrivage);
        $editForm->handleRequest($request);

        // die("qq".count($originalElementArrivage));
        if ($editForm->isSubmitted() && $editForm->isValid()) {
// die('aass');
            $em = $this->getDoctrine()->getManager();
            $uow = $em->getUnitOfWork();
            $elementArrivages = $arrivage->getElementArrivages();
            $arrivageOriginal = $uow->getOriginalEntityData($arrivage);
            # Unite of work doesn't care about Collection:
            // die("asa".count($arrivageOriginal['elementArrivages']));
            $error = false;
            // echo "c=".count($elementArrivages)."/".count($originalElementArrivage);
            foreach( $elementArrivages as $elementArrivage){
              $originalElt = $uow->getOriginalEntityData($elementArrivage);
              // echo $elementArrivage->getQuantiteVendu()."/".$elementArrivage->getQuantite() ."<br />";
              // echo $originalElt['quantiteVendu']."/".$originalElt['quantite'] ."<br />--<br />";

              if ( $originalElt && $elementArrivage->getQuantite() < $originalElt['quantiteVendu'] ){
                $error = true;
                $this->addFlash(
                    'danger',
                    'Vous avez déja vendu '.$originalElt['quantiteVendu'].'et déclarer un nombre d\'unité perdu de '.$originalElt['totalPerdu'].' de '.$elementArrivage->getProduit()->getName()." de Cet Arrivage."
                );
              }
               //print_r($elementArrivage->getMontant());
              $elementArrivage->setArrivage($arrivage);
              unset($originalElementArrivage[$elementArrivage->getId()]);
            }
            // echo "<br />c2=".count($elementArrivages)."/".count($originalElementArrivage);
            // echo"<br />//".count($arrivage->getElementArrivages());
            foreach($originalElementArrivage as $id => $val){
              if( $val != 0){ // > 0
                $error = true;
                $this->addFlash(
                    'danger',
                    'Vous avez déja vendu '.$backup[$id]->getQuantiteVendu().' et déclarer un nombre d\'unité perdu de '.$backup[$id]->getTotalPerdu().' de '.$backup[$id]->getProduit()->getName()." de Cet Arrivage."
                );
                $arrivage->addElementArrivage($backup[$id]);

                // $editForm = $this->createForm('AppBundle\Form\ArrivageType', $arrivage);
                $editForm = $this->createForm('AppBundle\Form\ArrivageType', $arrivage, array(
                  'action' => $this->generateUrl('arrivage_edit',array('id' => $arrivage->getId())),
                  'method' => 'POST',
                ));
                // echo"<br />#".count($arrivage->getElementArrivages());
                // echo "<br />zz=".$val."/".$backup[$id]->getQuantiteRestante()."<br />";
              }
            }
            // die("<br />=".count($arrivage->getElementArrivages()));
            // die('qaq');
            if($error){
              $this->addFlash(
                  'info',
                  "Si vous y insister, vous devez supprimer les ventes relatives à cet element d'arrivage."
              );
              // $this->addFlash(
              //     'warning',
              //     " NB: Vous pouvez vous appuiyer sur la date de l'arrivage pour déduire les ventes relatives à cet element d'arrivage dans la page 'Ventes'"
              // );
// die('aaa');
              return $this->render('arrivage/edit.html.twig', array(
                  'arrivage' => $arrivage,
                  'form' => $editForm->createView(),
                  'delete_form' => $deleteForm->createView(),
              ));
            }
            // die('aaq');
            $arrivage->prePersistOrUpdate();
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('arrivage_show', array('id' => $arrivage->getId()));
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
            $error = false;
            foreach($arrivage->getElementArrivages() as $eltArrivage){
              if( $eltArrivage->getQuantiteRestante() < $eltArrivage ->getQuantite() ){
                $error = true;
                $this->addFlash(
                    'danger',
                    'Vous avez déja vendu '.$eltArrivage->getQuantiteVendu().' et déclarer un nombre d\'unité perdu de '.$eltArrivage->getTotalPerdu().' de '.$eltArrivage->getProduit()->getName()." de Cet Arrivage."
                );
              }
            }

            if($error){
              $this->addFlash(
                  'info',
                  "Si vous y insister, vous devez supprimer les ventes ainsi que les pertes relatives à chaque element de cette arrivage."
              );
              // $this->addFlash(
              //     'warning',
              //     " NB: Vous pouvez vous appuiyer sur la date de l'arrivage pour déduire les ventes relatives à cet element d'arrivage dans la page 'Ventes'"
              // );

              // $editForm = $this->createForm('AppBundle\Form\ArrivageType', $arrivage);
              $editForm = $this->createForm('AppBundle\Form\ArrivageType', $arrivage, array(
                'action' => $this->generateUrl('arrivage_edit',array('id' => $arrivage->getId())),
                'method' => 'POST',
              ));

              $deleteForm = $this->createDeleteForm($arrivage);

              return $this->render('arrivage/edit.html.twig', array(
                  'arrivage' => $arrivage,
                  'form' => $editForm->createView(),
                  'delete_form' => $deleteForm->createView(),
              ));
            }

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
