<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Vente;
use AppBundle\Entity\ElementVente;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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

      // $myfile = fopen("testfile.txt", "w");


      // Set parameters
      // $apikey = '4c143f72-6ca0-4010-8870-338f8ccfa4ae';
      // $value = '<title>Test PDF conversion</title>This is the body'; // can aso be a url, starting with http..
      //
      // // Convert the HTML string to a PDF using those parameters.  Note if you have a very long HTML string use POST rather than get.  See example #5
      // $result = file_get_contents("http://api.html2pdfrocket.com/pdf?apikey=" . urlencode($apikey) . "&value=" . urlencode($value));
      //
      // // Save to root folder in website
      // file_put_contents('mypdf-1.pdf', $result);


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
// print_r(count($monstock)); die('qSq');
        foreach ($monstock as $elementArrivage){
          $eltVente = new ElementVente();
          $eltVente->setElementArrivage($elementArrivage);
          $eltVente->setVente($vente);
          $vente->addElementsVente($eltVente);
        }
  // print_r(count($vente->getElementsVente())); die('qqqs');
        $form = $this->createForm('AppBundle\Form\VenteType', $vente);
        $form->handleRequest($request);
 // print_r(count($vente->getElementsVente())); die('qqqs');
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
        //print_r(count($vente->getElementsVente()));
        //print_r($vente->getElementsVente()[0]->getQuantite());
        // die("aaaa");

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

        $pdfUrl = $this->generateUrl('facture', array("id"=>$vente->getId()), UrlGeneratorInterface::ABSOLUTE_URL);

        return $this->render('vente/show.html.twig', array(
            'vente' => $vente,
            'delete_form' => $deleteForm->createView(),
            'form' => $editForm->createView(),
            'pdfUrl' => $pdfUrl,
        ));
    }

    // /**
    //  * Creates a new vente entity.
    //  *
    //  * @Route("/facture/twig/{id}", name="facture_pdf")
    //  * @Method({"GET"})
    //  */
    //  public function facture(Vente $vente){
    //    $produitsFacture = array();
    //
    //    foreach ($vente->getElementsVente() as $elt){
    //      if( !array_key_exists($elt->getElementArrivage()->getProduit()->getName(), $produitsFacture )){
    //        $produitsFacture[$elt->getElementArrivage()->getProduit()->getName()]['prixUnit'] = $elt->getPrixUnit();
    //        $produitsFacture[$elt->getElementArrivage()->getProduit()->getName()]['montant'] = $elt->getMontant();
    //        $produitsFacture[$elt->getElementArrivage()->getProduit()->getName()]['quantite'] = $elt->getQuantite();
    //      }else{
    //        $produitsFacture[$elt->getElementArrivage()->getProduit()->getName()]['montant'] += $elt->getMontant();
    //        $produitsFacture[$elt->getElementArrivage()->getProduit()->getName()]['quantite'] += $elt->getQuantite();
    //      }
    //    }
    //
    //    $montantGlobal =  $vente->getMontant();
    //
    //    $html = $this->renderView('vente/invoice.html.twig', array(
    //      "produits" => $produitsFacture,
    //      "id" => $vente->getId(),
    //      "montantGlobal" => $montantGlobal
    //    ));
    //
    //    $filename = sprintf('test-%s.pdf', date('Y-m-d'));
    //
    //    // return new Response(
    //    //     $this->get('knp_snappy.pdf')->getOutput('http:\\www.google.com'),
    //    //     200,
    //    //     [
    //    //         'Content-Type'        => 'application/pdf',
    //    //         'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
    //    //     ]
    //    // );
    //
    //    //$this->get('knp_snappy.pdf')->generate('http://www.google.fr', 'file.pdf');
    //
    //    return new PdfResponse(
    //        $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
    //        'file.pdf'
    //    );
    //  }

    /**
     * Creates a new vente entity.
     *
     * @Route("/facture/{id}", name="facture")
     * @Method({"GET"})
     */
    public function downloadAction($id){

      $em = $this->getDoctrine()->getManager();
      $vente = $em->getRepository('AppBundle:Vente')->find($id);


      $produitsFacture = array();
      foreach ($vente->getElementsVente() as $elt){
        if( !array_key_exists($elt->getElementArrivage()->getProduit()->getName(), $produitsFacture )){
          $produitsFacture[$elt->getElementArrivage()->getProduit()->getName()]['prixUnit'] = $elt->getPrixUnit();
          $produitsFacture[$elt->getElementArrivage()->getProduit()->getName()]['montant'] = $elt->getMontant();
          $produitsFacture[$elt->getElementArrivage()->getProduit()->getName()]['quantite'] = $elt->getQuantite();
        }else{
          $produitsFacture[$elt->getElementArrivage()->getProduit()->getName()]['montant'] += $elt->getMontant();
          $produitsFacture[$elt->getElementArrivage()->getProduit()->getName()]['quantite'] += $elt->getQuantite();
        }
      }
      $montantGlobal =  $vente->getMontant();

      $filePath = "facture/facture_".$vente->getId().".pdf";
      $this->get('knp_snappy.pdf')->generateFromHtml(
        $this->renderView('vente/invoice.html.twig', array(
          "produits" => $produitsFacture,
          "id" => $vente->getId(),
          "montantGlobal" => $montantGlobal,
          "base_dir" => $this->get('kernel')->getRootDir() . '/../web',
          "vente" => $vente,
        )),
          $filePath,
          array(
            'orientation' => 'landscape',
             'enable-javascript' => true,
             'javascript-delay' => 1000,
             'no-stop-slow-scripts' => true,
             'no-background' => false,
             'lowquality' => false,
             'encoding' => 'utf-8',
             'images' => true,
             'cookie' => array(),
             'dpi' => 300,
             'image-dpi' => 300,
             'enable-external-links' => true,
             'enable-internal-links' => true,
             // 'page-height' => 124 * 3,
             // 'page-width'  => 192 * 3,
             'page-size' => 'A4',
          ),
          true

      );

      return new BinaryFileResponse($filePath);

      // return new PdfResponse(
      //     $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
      //     'file.pdf'
      // );



      // $urlFacture = $this->generateUrl('facture_pdf', array('id'=>$id), UrlGeneratorInterface::ABSOLUTE_URL);
      // $pdfPath = 'facture/facture_'.$id.'.pdf';
      // $filename = 'facture_'.$id.'.pdf';
      //
      // $connector = $this->get('schoenef_html_to_pdf.connector');
      // $connector->saveUrlAsPdf('http://www.google.com', $pdfPath , ['dpi' => 96]);
      //
      // return $this->file($pdfPath, $filename , ResponseHeaderBag::DISPOSITION_INLINE);
    }

    // /**
    //  * Export to PDF
    //  *
    //  * @Route("/pdf", name="demo_pdf")
    //  */
    // public function pdfAction()
    // {
    //
    //
    //     $filename = sprintf('test-%s.pdf', date('Y-m-d'));
    //
    //     return new Response(
    //         $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
    //         200,
    //         [
    //             'Content-Type'        => 'application/pdf',
    //             'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
    //         ]
    //     );
    // }


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
