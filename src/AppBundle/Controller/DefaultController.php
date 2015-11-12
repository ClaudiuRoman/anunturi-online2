<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Anunt;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * Lists all Anunt entities.
     *
     * @Route("/", name="anunt")//definirea rutei+definirea numelui acesteia
     * @Method("GET")//pot accesa ruta curenta doar prin Get
     * @Template()//html-ul nostru din resources->views->nume_controller->nume_actiune
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Anunt')->findBy([
            'isPublished'=>true
        ]);// ia repository-ul de la entitatea anunt + findall-returneaza toate inregistrarile din baza de date din inregistrarea curenta

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Finds and displays a Anunt entity.
     *
     * @Route("/show-anunt/{id}", name="anunt_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Anunt $entity */
        $entity = $em->getRepository('AppBundle:Anunt')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Anunt entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $entity->setNumberOfViews($entity->getNumberOfViews()+1);
        $em->persist($entity);
        $em->flush();
        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
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
