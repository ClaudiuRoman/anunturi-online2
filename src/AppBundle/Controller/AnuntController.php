<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Anunt;
use AppBundle\Form\AnuntType;

/**
 * Anunt controller.
 *
 * @Route("/anunt")
 */
class AnuntController extends Controller
{

    /**
     * Lists all Anunt entities.
     *
     * @Route("/", name="index_anunt")//definirea rutei+definirea numelui acesteia
     * @Method("GET")//pot accesa ruta curenta doar prin Get
     * @Template()//html-ul nostru din resources->views->nume_controller->nume_actiune
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Anunt')->findBy([
            'user'=>$this->getUser()
        ]);// ia repository-ul de la entitatea anunt + findall-returneaza toate inregistrarile din baza de date din inregistrarea curenta

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Anunt entity.
     *
     * @Route("/", name="anunt_create")
     * @Method("POST")
     * @Template("AppBundle:Anunt:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Anunt();
        $form = $this->createCreateForm($entity);//creare formular
        $form->handleRequest($request);//administrarea requestului

        if ($form->isValid()) { //verificarea validitatii formularului
            $em = $this->getDoctrine()->getManager();//
            $entity->setUser($this->getUser());
            $em->persist($entity);//precizarea informatiei catre "administrator"
            $em->flush();//introucerea in db

            return $this->redirect($this->generateUrl('anunt_show', array('id' => $entity->getId())));//id trece in entity getID
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Anunt entity.
     *
     * @param Anunt $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Anunt $entity)
    {
        $form = $this->createForm(new AnuntType(), $entity, array(
            'action' => $this->generateUrl('anunt_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Anunt entity.
     *
     * @Route("/new", name="anunt_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Anunt();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }



    /**
     * Displays a form to edit an existing Anunt entity.
     *
     * @Route("/{id}/edit", name="anunt_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Anunt')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Anunt entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Anunt entity.
    *
    * @param Anunt $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Anunt $entity)
    {
        $form = $this->createForm(new AnuntType(), $entity, array(
            'action' => $this->generateUrl('anunt_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Anunt entity.
     *
     * @Route("/{id}", name="anunt_update")
     * @Method("PUT")
     * @Template("AppBundle:Anunt:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Anunt')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Anunt entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('anunt_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Anunt entity.
     *
     * @Route("/{id}", name="anunt_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Anunt')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Anunt entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('anunt'));
    }

    /**
     * Creates a form to delete a Anunt entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('anunt_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
