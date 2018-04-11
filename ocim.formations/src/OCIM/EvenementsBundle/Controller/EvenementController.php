<?php

namespace OCIM\EvenementsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use OCIM\EvenementsBundle\Entity\Evenement;
use OCIM\EvenementsBundle\Entity\Event;
use OCIM\EvenementsBundle\Entity\Formation;
use OCIM\EvenementsBundle\Entity\TypeEvenement;
use OCIM\EvenementsBundle\Entity\Formule;
use OCIM\EvenementsBundle\Entity\evenementFormule;
use OCIM\EvenementsBundle\Form\EvenementType;
use OCIM\EvenementsBundle\Form\AjoutIntervenantType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Evenement controller.
 *
 */
class EvenementController extends Controller
{

    /**
     * Lists all Evenement entities.
     *
     */
    public function indexAction(Request $request, $type)
    {
      $em = $this->getDoctrine()->getManager();

      $id = $request->query->get('id');

      $className = "OCIMEvenementsBundle:".ucfirst($type);

      $qb = $em->createQueryBuilder('f')
        ->select('f, YEAR(f.dateDebut) AS annee')
        ->from($className, 'f')
        ->addOrderBy('annee', 'DESC')
        ->addOrderBy('f.dateDebut', 'ASC');

      $ac =  new ArrayCollection($qb->getQuery()->getResult());


      $qb = $em->createQueryBuilder('f')
        ->select('MIN(f.dateDebut), MAX(f.dateFin)')
        ->from('OCIMEvenementsBundle:Evenement', 'f');

      $datesMinMax = $qb->getQuery()->getResult();
      //exit(\Doctrine\Common\Util\Debug::dump($datesMinMax[0]));

      $evenements = new ArrayCollection();
      foreach($ac as $evenement){
        $evenements->add($evenement[0]);
      }

      // $evenements = $em->getRepository('OCIMEvenementsBundle:Evenement')->findBy(array(), array('YEAR(dateDebut)' => 'DESC'));
	    $types = $em->getRepository('OCIMEvenementsBundle:TypeEvenement')->findAll();

  		foreach($evenements as $evenement){
  			$evenement->_count = $em->getRepository('OCIMEvenementsBundle:Inscription')->countInscriptionsByEvenement($evenement->getId());
  		}

  		return $this->render('OCIMEvenementsBundle:Evenement:index.html.twig', array(
  			'evenements' => $evenements,
  			'types' => $types,
        'datesminmax' => $datesMinMax[0],
        'id' => $id,
        'eventType' => $type
  		));
    }
    /**
     * Creates a new Evenement entity.
     *
     */
    public function createAction(Request $request)
    {
        $classname = $this->get('request')->request->get('ocim_evenementsbundle_evenement');
        $classname = $classname['eventType'];
        $entity;
        switch ($classname) {
          case 'event':
            $entity = new Event();
            break;
          case 'formation':
            $entity = new Formation();
            break;
        }

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success','Evenement ajoutée.');

            return $this->redirect($this->generateUrl('evenement', array('id' => $entity->getId())));
        }

        return $this->render('OCIMEvenementsBundle:Evenement:new.html.twig', array(
            'entity' => $entity,
            'edit_form'   => $form->createView(),
        ));
    }

    public function ajoutTypeAction(Request $request){
      if($request->isXmlHttpRequest()){

        $em = $this->getDoctrine()->getManager();
        $type = new TypeEvenement();

        $str = json_decode($request->getContent());
        $str = ucfirst($str);

        if(is_string($str)){

          $type->setType($str);

          $em->persist($type);
          $em->flush();

          if($type->getId()){
            return new Response( $type->getId(), Response::HTTP_OK);
          }
          else{
            return new Response('Problème durant lenregistrement, Rechargez la page.', Response::HTTP_FAIL);
          }
        }

      }
    }

    public function indexIntervenantEvenementAction($idevenement){

      $em = $this->getDoctrine()->getManager();

      $entity = $em->getRepository('OCIMEvenementsBundle:Evenement')->find($idevenement);

      $intervenants = $em->getRepository('OCIMContactsBundle:Intervenant')->findAll();

      $form = $this->createForm(new AjoutIntervenantType(), $entity, array(
          'action' => $this->generateUrl('evenement_intervenants_update', array('idevenement' => $idevenement)),
          'method' => 'PUT',
      ));

      $form->add('submit', 'submit', array('label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-green btn-save')));

      return $this->render('OCIMEvenementsBundle:Evenement:addIntervenant.html.twig', array(
          'form' => $form->createView(),
          'intervenants' => $intervenants
      ));
    }

    public function updateIntervenantEvenementAction(Request $request, $idevenement){

      $em = $this->getDoctrine()->getManager();

      $evenement = $em->getRepository('OCIMEvenementsBundle:Evenement')->find($idevenement);

      if (!$evenement) {
          throw $this->createNotFoundException('Unable to find Evenement entity.');
      }

      $form = $this->createForm(new AjoutIntervenantType(), $evenement, array(
          'action' => $this->generateUrl('evenement_intervenants_update', array('idevenement' => $idevenement)),
          'method' => 'PUT',
      ));

      $form->add('submit', 'submit', array('label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-green btn-save')));

      $form->handleRequest($request);

      if ($form->isValid()) {

         $em->flush();

         $this->get('session')->getFlashBag()->add('notice','Modifications sauvegardées');

        return $this->redirect($this->generateUrl('inscription', array('idevenement' => $idevenement)) . "#intervenants");
        }

      $this->get('session')->getFlashBag()->add('error','Le formulaire contient des erreurs');

      return $this->render('OCIMEvenementsBundle:Evenement:addIntervenant.html.twig', array(
          'form' => $form->createView(),
      ));

    }

    /**
     * Creates a form to create a Evenement entity.
     *
     * @param Evenement $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Evenement $entity)
    {
        $form = $this->createForm(new EvenementType(), $entity, array(
            'action' => $this->generateUrl('evenement_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Créer la evenement', 'attr' => array('class'=>'btn btn-green btn-save')));

        return $form;
    }

    /**
     * Displays a form to create a new Evenement entity.
     *
     */
    public function newAction($type)
    {
        $entity = new Evenement();
        $entity->setEventType($type);
        $form   = $this->createCreateForm($entity);

        return $this->render('OCIMEvenementsBundle:Evenement:new.html.twig', array(
            'entity' => $entity,
            'edit_form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Evenement entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OCIMEvenementsBundle:Evenement')->find($id);
		    $entity->_count = $em->getRepository('OCIMEvenementsBundle:Inscription')->countInscriptionsByEvenement($id);
		    $inscriptions = $em->getRepository('OCIMEvenementsBundle:Inscription')->findAllByEvenement($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Evenement entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

		  // Stats des inscriptions
		  $statsInscriptions = array();
		  foreach($inscriptions as $inscription){

			if( !isset($statsInscriptions[$inscription->getDateInscription()->format('d-m-Y')])){
				$statsInscriptions[$inscription->getDateInscription()->format('d-m-Y')] = 1 ;
			}
			else{
				$statsInscriptions[$inscription->getDateInscription()->format('d-m-Y')] += 1 ;
			}
			ksort($statsInscriptions);
		}

        return $this->render('OCIMEvenementsBundle:Evenement:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
			'formules' => $entity->getFormules(),
			'inscriptions' => $statsInscriptions
        ));
    }

    /**
     * Displays a form to edit an existing Evenement entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OCIMEvenementsBundle:Evenement')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Evenement entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        // exit(\Doctrine\Common\Util\Debug::dump($entity->getEventType()));
        return $this->render('OCIMEvenementsBundle:Evenement:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
    * Creates a form to edit a Evenement entity.
    *
    * @param Evenement $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Evenement $entity)
    {
        $form = $this->createForm(new EvenementType(), $entity, array(
            'action' => $this->generateUrl('evenement_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Enregistrer', 'attr' => array('class'=>'btn btn-green btn-save')));

        return $form;
    }

    /**
     * Edits an existing Evenement entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OCIMEvenementsBundle:Evenement')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Evenement entity.');
        }

    		$ancienncesFF = new ArrayCollection();
    		foreach ($entity->getEvenementFormule() as $ff) {
    			$ancienncesFF->add($ff);
    		}

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

			   foreach ($ancienncesFF as $ff) {
				  if ($entity->getEvenementFormule()->contains($ff) == false) {
					     $em->remove($ff);
				  }
			   }

  			   $em->flush();

           $this->get('session')->getFlashBag()->add('notice','Modifications sauvegardées');

          return $this->redirect($this->generateUrl('evenement', array('id' => $id)));
          }

        $this->get('session')->getFlashBag()->add('error','Le formulaire contient des erreurs');

        return $this->render('OCIMEvenementsBundle:Evenement:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Evenement entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OCIMEvenementsBundle:Evenement')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Evenement entity.');
            }

            $em->remove($entity);
            $em->flush();
        }
        $this->get('session')->getFlashBag()->add('success','Evenement supprimée.');
        return $this->redirect($this->generateUrl('evenement'));
    }

    /**
     * Creates a form to delete a Evenement entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('evenement_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Supprimer', 'attr'=> array('class'=>'btn btn-red btn-delete')))
            ->getForm()
        ;
    }
}
