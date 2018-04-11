<?php

namespace OCIM\EvenementsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use OCIM\EvenementsBundle\Entity\evenementFormule;
use OCIM\EvenementsBundle\Entity\Evenement;
use OCIM\EvenementsBundle\Entity\ModeleChampPerso;
use OCIM\EvenementsBundle\Entity\ReponsesChampPerso;
use OCIM\EvenementsBundle\Entity\Inscription;
use OCIM\EvenementsBundle\Form\ChampPersoType;
use Doctrine\Common\Collections\ArrayCollection;
use OCIM\ContactsBundle\Entity\Personne;


/**
 * evenementFormule controller.
 *
 */
class ChampPersoController extends Controller
{

    /**
     * Lists all evenementFormule entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('OCIMEvenementsBundle:Evenement')->findAll();

		foreach($entities as $entity){
			$champPerso = $em->getRepository('OCIMEvenementsBundle:ModeleChampPerso')->findModelesByIdEvenement($entity->getId());
			$entity->setModeles(new ArrayCollection($champPerso));
		}

		foreach($entities as $entity){
			$countmodeles = 0;

			foreach($entity->getEvenementFormule() as $ff){
				$countmodeles += $ff->getModeles()->count();
			}
			$entity->_champPerso = $countmodeles;
		}

        return $this->render('OCIMEvenementsBundle:champPerso:index.html.twig', array(
            'entities' => $entities,
        ));
    }

	public function reponseAction(Request $request){
		if($request->isXmlHttpRequest()){

			$em = $this->getDoctrine()->getManager();

			$data = json_decode($request->getContent());

			$personne = new Personne();
			$personne = $em->getRepository('OCIMContactsBundle:Personne')->find($data[0]->idpersonne);

			$reponseChampPerso = new ReponsesChampPerso();
			// un objet ReponseChampPerso existe
			if(!empty($data[0]->idreponse)){
				$reponseChampPerso = $em->getRepository('OCIMEvenementsBundle:ReponsesChampPerso')->find($data[0]->idreponse);
			}

			// construction et enregistrement de la nouvelle reponse
			$nouvelleReponse;

		 	if($data[0]->type == "bool"){
				$nouvelleReponse = ($data[0]->reponse == "")? true : (($data[0]->reponse == "0")? null : false);
				if(is_null($nouvelleReponse)){
          $em->remove($reponseChampPerso);
        }
        $reponseChampPerso->setReponse($nouvelleReponse);
			}
			elseif($data[0]->type == "text"){
				$nouvelleReponse = $data[0]->reponse;
				$reponseChampPerso->setReponseText($nouvelleReponse);
			}


			if($reponseChampPerso->getId() == null){
				$reponseChampPerso->setPersonne($personne);
				$reponseChampPerso->setModele($em->getReference('OCIMEvenementsBundle:ModeleChampPerso', $data[0]->idmodele));
				$personne->addReponsesChampPerso($reponseChampPerso);
				$em->persist($reponseChampPerso);
			}

			$em->flush();

			$data[0]->idreponse = $reponseChampPerso->getId();
			$data[0]->reponse = $nouvelleReponse;

			return new Response( json_encode($data) , Response::HTTP_OK);

		}
	}

    /**
     * Creates a new evenementFormule entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new evenementFormule();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('champPerso_show', array('id' => $entity->getId())));
        }

        return $this->render('OCIMEvenementsBundle:champPerso:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a evenementFormule entity.
     *
     * @param evenementFormule $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(evenementFormule $entity)
    {
        $form = $this->createForm(new ChampPersoType(), $entity, array(
            'action' => $this->generateUrl('champPerso_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Créer'));

        return $form;
    }

    /**
     * Displays a form to create a new evenementFormule entity.
     *
     */
    public function newAction()
    {
        $entity = new evenementFormule();
        $form   = $this->createCreateForm($entity);

        return $this->render('OCIMEvenementsBundle:champPerso:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a evenementFormule entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OCIMEvenementsBundle:evenementFormule')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find evenementFormule entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OCIMEvenementsBundle:champPerso:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing evenementFormule entity.
     *
     */
    public function editAction($idevenement, $generation)
    {
        $em = $this->getDoctrine()->getManager();


        $evenement = $em->getRepository('OCIMEvenementsBundle:Evenement')->find($idevenement);

		if($generation == "generate"){

			$modeles = new ArrayCollection();

			// On détermine les variables nécessaires à la génération des objets ModeleChampPerso
			$dateDebut = $evenement->getDateDebut();
			$dateFin = $evenement->getDateFin();

			$dateDebut->setTime(00, 00);
			$dateFin->setTime(24, 00);

			$period = new \DatePeriod($dateDebut, new \DateInterval('P1D'), $dateFin);

			$journee = array();

			foreach($evenement->getEvenementFormule() as $ff){
				$journee['midi'][] = ($ff->getFormule()->getMidi())? $ff : false;
				$journee['soir'][] = ($ff->getFormule()->getSoir())? $ff : false;
				$journee['nuit'][] = ($ff->getFormule()->getNuit())? $ff : false;
			}

			//arrivée pour les intervenants
			$m = new ModeleChampPerso();
			$m->setDescription('Arrivée');
			$m->setTypeReponse('text');
			$m->setIntervenant(true);
			$evenement->addModele($m);

			foreach($period as $date)
			{
				foreach($journee as $key=>$j){
					$m = new ModeleChampPerso();

					foreach($j as $ff){
						if($ff){
							$m->addEvenementFormule($ff);
						}
					}
          $date->modify('+1 hour');
					$m->setIntervenant(true);
					$m->setDate($date);
					$m->setDescription(ucfirst($key));
					$m->setTypeReponse("bool");
					$evenement->addModele($m);
				}
			}

			//départ pour les intervenants
			$m = new ModeleChampPerso();
			$m->setDescription('Départ');
			$m->setTypeReponse('text');
			$m->setIntervenant(true);
			$evenement->addModele($m);

		}
		else{
			$modeles = $em->getRepository('OCIMEvenementsBundle:ModeleChampPerso')->findModelesByIdEvenement($idevenement);
			if(!$modeles){
				$modeles[] = new ModeleChampPerso();
				$evenement->setModeles(new ArrayCollection($modeles));
			}
			else{
				$evenement->setModeles(new ArrayCollection($modeles));
			}
		}

        $editForm = $this->createEditForm($evenement);
        $deleteForm = $this->createDeleteForm($idevenement);

        return $this->render('OCIMEvenementsBundle:champPerso:edit.html.twig', array(
            'entity'      => $evenement,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a evenementFormule entity.
    *
    * @param evenementFormule $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($entity)
    {
        $form = $this->createForm(new ChampPersoType($entity->getId()), $entity, array(
            'action' => $this->generateUrl('champPerso_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Enregistrer', 'attr'=> array('class'=>'btn btn-green btn-save')));

        return $form;
    }
    /**
     * Edits an existing evenementFormule entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OCIMEvenementsBundle:Evenement')->find($id);
        $modeles = $em->getRepository('OCIMEvenementsBundle:ModeleChampPerso')->findModelesByIdEvenement($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find evenementFormule entity.');
        }

        $entity->setModeles(new ArrayCollection($modeles));

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);



        if ($editForm->isValid()) {

    			//suppression des anciens modeles
    			foreach($modeles as $modele){
    				if(!$entity->getModeles()->contains($modele)){
    					// le modele a été supprimé, on supprime ses relations
    					foreach($modele->getEvenementFormule() as $ff){
    						$modele->removeEvenementFormule($ff);
    						$ff->removeModele($modele);
    					}
    					$entity->removeModele($modele);
    					$modele->setEvenement(null);
    					$em->remove($modele);
    				}
    				else{
    					//On recherche les modification sur les evenementFormule des modeles
    					foreach($entity->getEvenementFormule() as $ff){
    						if(!$modele->getEvenementFormule()->contains($ff)){
    							$ff->removeModele($modele);
    						}
      				}
      			}
      		}
  			//exit( \Doctrine\Common\Util\Debug::dump($entity->getModeles()[0]->getIntervenant()));


    			foreach($entity->getModeles() as $modele){
    				foreach($modele->getEvenementFormule() as $ff){
    					$ff->addModele($modele);
    				}
    				$modele->setEvenement(($modele->getIntervenant())? $entity : null);
    				if($modele->getEvenementFormule()->count() == 0){
    					if($modele->getIntervenant() === false){
    						$em->remove($modele);
    					}
    					else{
    						$em->persist($modele);
    					}
    				}
    			}

            $em->flush();
            $this->get('session')->getFlashBag()->add('success','Modifications enregistrées.');
              return $this->redirect($this->generateUrl('champPerso_edit', array('idevenement' => $id)));
        }

        return $this->render('OCIMEvenementsBundle:champPerso:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a evenementFormule entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OCIMEvenementsBundle:ModeleChampPerso')->findModelesByIdEvenement($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find evenementFormule entity.');
            }

			foreach($entity as $mo){
				$em->remove($mo);
			}

            $em->flush();
        }

        return $this->redirect($this->generateUrl('champPerso'));
    }

    /**
     * Creates a form to delete a evenementFormule entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('champPerso_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Supprimer', 'attr'=>array('class'=>'btn btn-red btn-delete')))
            ->getForm()
        ;
    }
}
