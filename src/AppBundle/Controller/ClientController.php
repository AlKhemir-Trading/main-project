<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Payement;

/**
 * Client controller.
 *
 * @Route("client")
 */
class ClientController extends Controller
{
    /**
     * Lists all client entities.
     *
     * @Route("/", name="client_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $clients = $em->getRepository('AppBundle:Client')->findAll();

        return $this->render('client/index.html.twig', array(
            'clients' => $clients,
        ));
    }

    /**
     * Creates a new client entity.
     *
     * @Route("/new", name="client_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $client = new Client();
        // $client->setPays('TN');
        $form = $this->createForm('AppBundle\Form\ClientType', $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($client);
            $em->flush();

            $this->addFlash(
                'success',
                'Client Crée avec Succés.'
            );
            return $this->redirectToRoute('client_show', array('id' => $client->getId()));
        }

        return $this->render('client/new.html.twig', array(
            'client' => $client,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a client entity.
     *
     * @Route("/{id}", name="client_show")
     * @Method("GET")
     */
    public function showAction(Client $client)
    {
        $deleteForm = $this->createDeleteForm($client);
        $editForm = $this->createForm('AppBundle\Form\ClientType', $client);

        #Payements:
        $em = $this->getDoctrine()->getManager();
        $payements = $em->getRepository('AppBundle:Payement')->findBy(array('client' => $client->getId()));

        #Form Payement:
        $payement = new Payement();
        $payement->setClient($client);
        // die('aaa'.$payement->getClient()->getName());
        $formPayement = $this->createForm('AppBundle\Form\PayementType', $payement);
        // $formPayement->handleRequest($request);
        //
        // $TotalePaye = $em->getRepository('AppBundle:Payement')->payementByClientId($client->getId())['Total'];
        // $TotaleAchete = $em->getRepository('AppBundle:Vente')->venteByClientId($client->getId())['Total'];
        //
        // $plusOuMoins = $TotalePaye - $TotaleAchete;

        return $this->render('client/show.html.twig', array(
            'client' => $client,
            'delete_form' => $deleteForm->createView(),
            'form' => $editForm->createView(),
            'formPayement' => $formPayement->createView(),
            'payements' => $payements,
            // 'plusOuMoins' => $plusOuMoins
        ));
    }

    /**
     * Displays a form to edit an existing client entity.
     *
     * @Route("/{id}/edit", name="client_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Client $client)
    {
        $deleteForm = $this->createDeleteForm($client);
        $editForm = $this->createForm('AppBundle\Form\ClientType', $client);
        $editForm->handleRequest($request);

        #Payements:
        $em = $this->getDoctrine()->getManager();
        $payements = $em->getRepository('AppBundle:Payement')->findBy(array('client' => $client->getId()));

        #Payement Delete Form
        $payementDeleteForm = $this->createFormBuilder()
            ->setAction($this->generateUrl('payement_delete', array('id' => 'id' )))
            ->setMethod('DELETE')
            ->getForm()
        ;

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                'warning',
                'Client Modifié avec Succés.'
            );
            return $this->redirectToRoute('client_show', array('id' => $client->getId()));
        }

        // $TotalePaye = $em->getRepository('AppBundle:Payement')->payementByClientId($client->getId())['Total'];
        // $TotaleAchete = $em->getRepository('AppBundle:Vente')->venteByClientId($client->getId())['Total'];
        // $plusOuMoins = $TotalePaye - $TotaleAchete;

        return $this->render('client/edit.html.twig', array(
            'client' => $client,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'payements' => $payements,
            'payementDeleteForm' => $payementDeleteForm->createView(),
            // 'plusOuMoins' => $plusOuMoins
        ));
    }

    /**
     * Deletes a client entity.
     *
     * @Route("/{id}", name="client_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Client $client)
    {
        $form = $this->createDeleteForm($client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($client);
            $em->flush();
            $this->addFlash(
                'danger',
                'Client Supprimé avec Succés.'
            );
        }

        return $this->redirectToRoute('client_index');
    }

    /**
     * Creates a form to delete a client entity.
     *
     * @param Client $client The client entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Client $client)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('client_delete', array('id' => $client->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
