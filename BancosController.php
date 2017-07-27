<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Bancos;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Banco controller.
 *
 * @Route("bancos")
 */
class BancosController extends Controller
{
    /**
     * Lists all banco entities.
     *
     * @Route("/", name="bancos_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $this->get('app.bitacora')->agregar("Ingresó a la Página Principal de Bancos");
        $em = $this->getDoctrine()->getManager();

        $bancos = $em->getRepository('AppBundle:Bancos')->findAll();

        return $this->render('AppBundle:Bancos:index.html.twig', array(
            'bancos' => $bancos,
        ));
    }

    /**
     * Creates a new banco entity.
     *
     * @Route("/new", name="bancos_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $this->get('app.bitacora')->agregar("Ingresó a Agregar Nuevo Banco");
       
        $banco = new Bancos();
        $form = $this->createForm('AppBundle\Form\BancosType', $banco);
        $form->handleRequest($request);
        
        $errors = false;
            if (!$errors) {
                foreach ($form->getErrors(true) as $key => $error) {
                    $errors[] = $error->getMessage();
                    $this->addFlash('error', $errors[$key]);
                $this->get('app.bitacora')->agregar("Intentó Crear un Nuevo Nombre de Banco".
                        $banco->getNombre().", ".$errors[$key]);
                }
            }
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($banco);
            $em->flush($banco);
            $this->addFlash('success', "Nombre de Banco Agregado Satisfactoriamente");
            $this->get('app.bitacora')->agregar("Agregó Nuevo Nombre de Banco ".$banco->getNombre());
       

            return $this->redirectToRoute('bancos_show', array('id' => $banco->getId()));
        }

        return $this->render('AppBundle:Bancos:new.html.twig', array(
            'banco' => $banco,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a banco entity.
     *
     * @Route("/{id}", name="bancos_show")
     * @Method("GET")
     */
    public function showAction(Bancos $banco)
    {
        $this->get('app.bitacora')->agregar("Ingresó a Mostrar Banco: ".$banco->getNombre());
        
        return $this->render('AppBundle:Bancos:show.html.twig', array(
            'banco' => $banco,
            
        ));
    }

    /**
     * Displays a form to edit an existing banco entity.
     *
     * @Route("/{id}/edit", name="bancos_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Bancos $banco)
    {
      $this->get('app.bitacora')->agregar("Ingresó a Editar Banco: ".$banco->getNombre());
      $oldBanco = $banco->getNombre();
        $editForm = $this->createForm('AppBundle\Form\BancosType', $banco);
        $editForm->handleRequest($request);
        
        
        $errors = false;
            if (!$errors) {
                foreach ($editForm->getErrors(true) as $key => $error) {
                    $errors[] = $error->getMessage();
                    $this->addFlash('error', $errors[$key]);
                $this->get('app.bitacora')->agregar("Intentó Editar el Nombre de Banco".
                        $banco->getNombre().", ".$errors[$key]);
                }
            }
        
        
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush($banco);

            $this->addFlash('success', "Nombre de Banco Modificado Exitosamente");

            $this->get('app.bitacora')->agregar("Editó el nombre del Banco: ".$oldBanco." a ".$banco->getNombre());
      
            return $this->redirectToRoute('bancos_edit', array('id' => $banco->getId()));
        }

        return $this->render('AppBundle:Bancos:edit.html.twig', array(
            'banco' => $banco,
            'edit_form' => $editForm->createView(),
        ));
    }

   
}
