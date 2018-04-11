<?php

namespace OCIM\EvenementsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use OCIM\EvenementsBundle\Entity\Formule;
use OCIM\EvenementsBundle\Form\FormuleType;

/**
 * Formule controller.
 *
 */
class FormuleController extends Controller
{

    /**
     * Lists all Formule entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->query->get('id');
        $entities = $em->getRepository('OCIMEvenementsBundle:Formule')->findAll();

		foreach($entities as $entity){
			//nombre d'inscriptions liées à la formule
			$qb = $em->createQueryBuilder();
			$qb->select('count(i.id)');
			$qb->from('OCIMEvenementsBundle:Inscription','i');
			$qb->join('OCIMEvenementsBundle:evenementFormule', 'f', 'WITH', 'f.id = i.evenementformule');
			$qb->where('f.formule = :id');
			$qb->setParameter('id', $entity->getId());
			$entity->_countInscriptions = $qb->getQuery()->getSingleResult();

			// nombre de evenements liées à la formule
			$qb = $em->createQueryBuilder();
			$qb->select('count(ff.id)');
			$qb->from('OCIMEvenementsBundle:evenementFormule','ff');
			$qb->where('ff.formule = :id');
			$qb->setParameter('id', $entity->getId());
			$entity->_countEvenements = $qb->getQuery()->getSingleResult();
		}

        return $this->render('OCIMEvenementsBundle:Formule:index.html.twig', array(
            'entities' => $entities,
            'id' => $id,
        ));
    }
    /**
     * Creates a new Formule entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Formule();
        if(!$request->isXmlHttpRequest()){
          $form = $this->createCreateForm($entity);
          $form->handleRequest($request);

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success','Formule créée.');
            return $this->redirect($this->generateUrl('formule', array('id' => $entity->getId())));
          }

          return $this->render('OCIMEvenementsBundle:Formule:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
          ));
        }
        // sinon c'est de l'ajax -> page evenement/new
        else{
          $data = $request->getContent();
          $data = json_decode($data);

          $entity->setDescription($data['0']->description);
          $entity->setTarif($data['0']->tarif);
          $entity->setNuit((bool)$data['0']->hebergement);
          $entity->setMidi((bool)$data['0']->midi);
          $entity->setSoir((bool)$data['0']->soir);

          //enregistrement bdd
          $em = $this->getDoctrine()->getManager();
          $em->persist($entity);
          $em->flush();

          if($entity->getId()){
            return new Response($entity->getId(), Response::HTTP_OK);
          }
          else{
            return new Response("Problème. Enregistrement annulé");
          }
        }

    }

    /**
     * Creates a form to create a Formule entity.
     *
     * @param Formule $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Formule $entity)
    {
        $form = $this->createForm(new FormuleType(), $entity, array(
            'action' => $this->generateUrl('formule_create'),
            'method' => 'POST',
			'attr' => array('class' => 'forms')
        ));

        $form->add('submit', 'submit', array('label' => 'Créer', 'attr'=> array('class'=> 'btn btn-green btn-save')));

        return $form;
    }

    /**
     * Displays a form to create a new Formule entity.
     *
     */
    public function newAction()
    {
        $entity = new Formule();
        $form   = $this->createCreateForm($entity);

        return $this->render('OCIMEvenementsBundle:Formule:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Formule entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OCIMEvenementsBundle:Formule')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Formule entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OCIMEvenementsBundle:Formule:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Formule entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OCIMEvenementsBundle:Formule')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Formule entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OCIMEvenementsBundle:Formule:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Formule entity.
    *
    * @param Formule $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Formule $entity)
    {
        $form = $this->createForm(new FormuleType(), $entity, array(
            'action' => $this->generateUrl('formule_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Enregistrer', 'attr'=> array('class'=>'btn btn-green btn-save')));

        return $form;
    }
    /**
     * Edits an existing Formule entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OCIMEvenementsBundle:Formule')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Formule entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice','Modifications sauvegardées');
            return $this->redirect($this->generateUrl('formule', array('id' => $id)));
        }
        $this->get('session')->getFlashBag()->add('error','Le formulaire contient des erreurs');
        return $this->render('OCIMEvenementsBundle:Formule:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Formule entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OCIMEvenementsBundle:Formule')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Formule entity.');
            }

            $em->remove($entity);
            $em->flush();
        }
        $this->get('session')->getFlashBag()->add('success','Formule supprimée.');
        return $this->redirect($this->generateUrl('formule'));
    }

    /**
     * Creates a form to delete a Formule entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('formule_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Supprimer', 'attr'=> array('class'=>'btn btn-red btn-delete')))
            ->getForm()
        ;
    }
}
