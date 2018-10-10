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

        foreach ($monstock as $elementArrivage){
          $eltVente = new ElementVente();
          $eltVente->setElementArrivage($elementArrivage);
          //$elementArrivage->addElementsVente($eltVente);----
          $eltVente->setVente($vente);
          $vente->addElementsVente($eltVente);
        }

        $form = $this->createForm('AppBundle\Form\VenteType', $vente);
        //print_r( $form->getData("montant")); die('qs');
        // die('tt'.$request->request->get('date'));
        //print_r($request->request);
        // die('qq');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vente->getClient()->addVente($vente);

            $elementsVente = $vente->getElementsVente();
            foreach( $elementsVente as $element){
                if ( $element->getQuantite() == 0 || $element->getPrixUnit() == 0 ){
                  $vente->removeElementsVente($element);
                }else{
                  $element->getElementArrivage()->addElementsVente($element);
                  //$em->persist($element->getElementArrivage());
                }
            }

            $dateNow = new \DateTime();
            $hours = $dateNow->format('H');
            $minutes = $dateNow->format('i');
            $vente->getDate()->setTime($hours,$minutes);
            // $dateNow = new \DateTime();
            // $vente->setDate($dateNow);

            // MAJ QTE VENDU ELEMENT_ARRIVAGE
            foreach ($elementsVente as $elementVente){
              $elementVente->getElementArrivage()->updateQutantiteVendu();
            }

            #payer vente auto
            // echo "aaa".is_numeric($vente->getClient()->getPlusOuMoins())."/".is_numeric($vente->getMontant())."<br />";
            // die("qsd".$vente->getClient()->getPlusOuMoins());
            if( $vente->getClient()->getPlusOuMoins() > 0 ){
              if ($vente->getClient()->getPlusOuMoins() >= $vente->getMontant()){
                $vente->setMontantPaye($vente->getMontant());
                //$vente->getClient()->setPlusOuMoins(0);
              }else{
                $vente->setMontantPaye($vente->getClient()->getPlusOuMoins());
                //$vente->getClient()->setPlusOuMoins($vente->getClient()->getPlusOuMoins() - $vente->getMontant());
              }
            }
            //die("aaa".$vente->getMontantPaye());
            $vente->getClient()->updatePlusOuMoins();
            $em->persist($vente);
            $em->flush();

            $this->refreshDestribution2($vente);
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
      $em = $this->getDoctrine()->getManager();
      $monstock = $em->getRepository('AppBundle:ElementArrivage')->monstockIndex();

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
          if ( $element->getQuantite() == 0 || $element->getPrixUnit() == 0 ){
            $vente->removeElementsVente($element);
            $element->getElementArrivage()->removeElementsVente($element);
          }elseif (! $element->getElementArrivage()->getElementsVente()->contains($element)){
            $element->getElementArrivage()->addElementsVente($element);
          }
          $element->getElementArrivage()->updateQutantiteVendu();
        }

        // // MAJ QTE VENDU ELEMENT_ARRIVAGE
        // foreach ($elementsVente as $elementVente){
        //   $elementVente->getElementArrivage()->updateQutantiteVendu();
        // }

        #payement new dispertion:
        $this->refreshDistribution($vente);

        $vente->getClient()->updatePlusOuMoins();
        $em->persist($vente);
        $em->flush();

        $this->refreshDestribution2($vente);
        $em->persist($vente);
        $em->flush();

        // echo "<br />after flush:".$vente->getClient()->getPlusOuMoins()."<br />";
        // die("ss".$vente->getClient()->getPlusOuMoins());
        return $this->redirectToRoute('vente_show', array('id' => $vente->getId()));
      }
      // elseif ($editForm->isSubmitted()){
      //   // foreach ($vente->getElementsVente() as $elt) {
      //       $em->refresh($vente);
      //       $editForm = $this->createForm('AppBundle\Form\VenteType', $vente);
      //   // }
      // }

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

            $elementsVente = $vente->getElementsVente();
            // MAJ QTE VENDU ELEMENT_ARRIVAGE
            foreach ($elementsVente as $elementVente){
              //remove elementVente from elementArrivage
              $elementVente->getElementArrivage()->removeElementsVente($elementVente);
              $elementVente->getElementArrivage()->updateQutantiteVendu();

              // $QteVendu = 0;
              // foreach($elementVente->getElementArrivage()->getElementsVente() as $eltVente){
              //   $QteVendu += $eltVente->getQuantite();
              // }
              // $elementVente->getElementArrivage()->setQuantiteVendu($QteVendu);

            }

            $vente->setMontant(0);
            $this->refreshDistribution($vente);

            $vente->getClient()->removeVente($vente);
            $vente->getClient()->updatePlusOuMoins();
            $em->flush();

            $this->refreshDestribution2($vente);
            $em->flush();

            $em->remove($vente);
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

    # called when a vente is updated an its montant_paye > montant or it was already payed but when updated it needs to get payement from the lastest to get payed
    private function refreshDistribution(Vente $vente){
      $em = $this->getDoctrine()->getManager();
      $venteRepository = $em->getRepository('AppBundle:Vente');
      $originalMontant = $venteRepository->montantOriginal($vente->getId())[0]['montant'];
      $plusOuMoins = $vente->getClient()->getPlusOuMoins();

      #Cas Exces:
      if($vente->getMontantPaye() > $vente->getMontant()){
        $diff = $vente->getMontantPaye() - $vente->getMontant();
        $vente->setMontantPaye($vente->getMontant());
        //get ventes not paye asc
        // if diff>0 pm += diff
        if($plusOuMoins >= 0){ // OK;
          //$vente->getClient()->setPlusOuMoins($plusOuMoins+$diff);
          $vente->setMontantPaye($vente->getMontant());
        }else{ // OK
          //get Ventes Inpayes ASC Until somme montant > diffMontant
          $limitRequest = 0;
          $ventesPayement = array();
          $sumMontantRestant = 0;

          do {
            $limitRequest += 1;
            //$ventesPayement = $venteRepository->ventesNonPayesUntil($vente->getClient()->getId(),$limitRequest,$vente->getDate()->format("Y-m-d H:i:s"));
            $ventesPayement = $venteRepository->ventesNonPayesUntil($vente->getClient()->getId(),$limitRequest,$vente->getId());
            $sumMontantRestant = 0;
            foreach ($ventesPayement as $venteX){
              $sumMontantRestant += $venteX->getMontant() - $venteX->getMontantPaye();
            }
            // echo $sumMontantRestant ."/". $diff."<br />";
            // echo count($ventesPayement) ."/". $limitRequest."<br />";
            // echo $limitRequest."/".count($ventesPayement)."<br />";
            // if ($limitRequest == 5)
            // die('aazzeeerr');
          } while ($sumMontantRestant < $diff && count($ventesPayement) == $limitRequest );
          //die('2222sss');
          // echo "l".$limitRequest."<br />";
          // echo "qq".$sumMontantRestant;
          // die('aaz');
          // echo "****".$limitRequest."<br />";
          //die("ciaos".count($ventesPayement));
          foreach($ventesPayement as $venteZ){
            if ( $diff >= $venteZ->getMontant() - $venteZ->getMontantPaye() ){
              $diff -= $venteZ->getMontant() - $venteZ->getMontantPaye();
              $venteZ->setMontantPaye($venteZ->getMontant());
            }else{
              $venteZ->setMontantPaye($venteZ->getMontantPaye() + $diff);
              $diff = 0;
            }
            //$em->persist($venteZ);
          }
          // if ( $diff > 0 ){
          //   // $vente->getClient()->setPlusOuMoins($plusOuMoins + $diff);
          //   $vente->getClient()->updatePlusOuMoins();
          // }

        }
      # Cas Perte:
      //else if montantPaye < montant && cette vente n'etait pas au top des vente payé cad qu'elle doit prendre le montant de ses successeur pour qu'elle soit payé
      }elseif( $vente->getMontantPaye() < $vente->getMontant() && $vente->getMontantPaye() == $originalMontant ){
        $diff = $vente->getMontant() - $vente->getMontantPaye();

        if($plusOuMoins > 0){
          if ($plusOuMoins >= $diff){ //OK
            //$vente->getClient()->setPlusOuMoins($plusOuMoins-$diff);
            $vente->setMontantPaye($vente->getMontant());
            $diff = 0;
          }else{ // OK2
            $vente->setMontantPaye($vente->getMontantPaye() + $plusOuMoins);
            $diff -= $plusOuMoins;
            //$vente->getClient()->setPlusOuMoins(0);
          }
        }

        if($diff > 0){ //  OK2
          //get ventes paye desc where date > vente.date and montant_paye > 0
          $limitRequest = 0;
          $ventesPayement = array();
          $sumMontantPaye = 0;

          do {
            $limitRequest += 1;
            $ventesPayement = $venteRepository->ventesPayesUntil($vente->getClient()->getId(),$limitRequest,$vente->getId());
            $sumMontantPaye = 0;
            foreach ($ventesPayement as $venteY){
              $sumMontantPaye += $venteY->getMontantPaye();
            }

            // echo $limitRequest."/".count($ventesPayement)."<br />";
            // if ($limitRequest == 5)
            // die('aazzeeerr');
          } while ($sumMontantPaye < $diff && count($ventesPayement) == $limitRequest );

          $saveDiff = $diff;
          foreach ($ventesPayement as $venteY) {
            if ($venteY->getMontantPaye() >= $diff){
              $venteY->setMontantPaye($venteY->getMontantPaye() - $diff);
              $diff = 0;
            }else{
              $diff -= $venteY->getMontantPaye();
              $venteY->setMontantPaye(0);
            }
            //$em->persist($venteY);
          }

          $vente->setMontantPaye($vente->getMontant()-$diff);

          // if($diff > 0)
          //   $vente->getClient()->setPlusOuMoins($plusOuMoins-$diff);

          //$em->persist($vente);
        }

        //die('3eme Cas');
      }

    }
    # check is there an erlier vente payed before a lastest vente, if its the case, refresh destribution
    # Called when a date vente is changed as earliest or a vente created as earliest
    function refreshDestribution2(Vente $vente){
      $em = $this->getDoctrine()->getManager();
      $venteRepository = $em->getRepository('AppBundle:Vente');

      $lastVenteMontantPayeNotZero = null;
      $firstVenteUmpayed = null;

      $res1 = $venteRepository->getLastVenteMontantPayeNotZero($vente->getClient()->getId());
      if (!empty($res1))
        $lastVenteMontantPayeNotZero = $res1[0];

      $res2 = $venteRepository->firstVenteUmpayed($vente->getClient()->getId());
      if(!empty($res2))
        $firstVenteUmpayed = $res2[0];

      if( $lastVenteMontantPayeNotZero
          && $firstVenteUmpayed
          && $lastVenteMontantPayeNotZero->getId() != $firstVenteUmpayed->getId()
          &&  $lastVenteMontantPayeNotZero->getDate() > $firstVenteUmpayed->getDate() ){

            //get ventes from first umpayed to last montant paye > 0
            $vts = $venteRepository->getVentesToTreat($vente->getClient()->getId(),$firstVenteUmpayed->getDate()->format('Y-m-d H:i:s'),$lastVenteMontantPayeNotZero->getDate()->format("Y-m-d H:i:s"));

            $i = 0;
            $j = count($vts) - 1;
            // die($i."//".$j);
            while($i < $j){
              //echo 'zz'.$i."/".$j;
              // die('zz'.$i.$j.$vts[$i]->getMontantPaye());
              if($vts[$i]->getMontantPaye() > 0){
                if( $vts[$j]->getMontantPaye() < $vts[$j]->getMontant() ){
                  $restant = $vts[$j]->getMontant() - $vts[$j]->getMontantPaye();
                  if ( $restant <= $vts[$i]->getMontantPaye() ){
                    $vts[$i]->setMontantPaye( $vts[$i]->getMontantPaye() - $restant );
                    $vts[$j]->setMontantPaye($vts[$j]->getMontant());
                    $j--;
                    // die($j."J1");
                  }else{ // restant > v[i].montantPaye()
                    $vts[$j]->setMontantPaye( $vts[$j]->getMontantPaye() + $vts[$i]->getMontantPaye() );
                    $vts[$i]->setMontantPaye(0);
                    $i++;
                    // die($i."I1");
                  }
                }else{
                  $j--;
                }
              }else{
                $i++;
                // die($i."I2");
              }
            }
            // die('qqqs');
      }

    }
}
