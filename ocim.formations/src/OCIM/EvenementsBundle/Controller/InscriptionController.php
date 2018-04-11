<?php

namespace OCIM\EvenementsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use OCIM\EvenementsBundle\Entity\Inscription;
use OCIM\EvenementsBundle\Form\InscriptionType;
use OCIM\EvenementsBundle\Form\FinancementType;
use OCIM\ContactsBundle\Entity\Signataire;
use OCIM\ContactsBundle\Entity\Intervenant;
use OCIM\ContactsBundle\Entity\Adresse;
use OCIM\EvenementsBundle\Entity\ReponsesChampPerso;



/**
* Inscription controller.
*
*/
class InscriptionController extends Controller
{


  /**
  * liste toutes les entités en fonction d'une evenement (id passé en paramètre)
  *
  */
  public function indexAction(Request $request, $idevenement)
  {
    $em = $this->getDoctrine()->getManager();

    $id = $request->query->get('id');

    $entities = $em->getRepository('OCIMEvenementsBundle:Inscription')->findAllByEvenement($idevenement);
    $evenement = $em->getRepository('OCIMEvenementsBundle:Evenement')->find($idevenement);
    $champPerso = $em->getRepository('OCIMEvenementsBundle:ModeleChampPerso')->findModelesByIdEvenement($idevenement);

    //exit(\Doctrine\Common\Util\Debug::dump($champPerso));

    return $this->render('OCIMEvenementsBundle:Inscription:index.html.twig', array(
      'entities' => $entities,
      'evenement' => $evenement,
      'champPerso' => $champPerso,
      'id' => $id,
    ));
  }

  public function ordreAction(Request $request, $idevenement){
    if($request->isXmlHttpRequest()){

      $em = $this->getDoctrine()->getManager();

      $data = json_decode($request->getContent());

      foreach($data as $d){
        $inscription = $em->getRepository('OCIMEvenementsBundle:Inscription')->find($d->id);
        $inscription->setOrdre($d->ordre);
      }

      $em->flush();
      return new Response( 'ok' , Response::HTTP_OK);

    }
  }

  public function switchStagiaireAction($idinscription, $idevenement){

    $em = $this->getDoctrine()->getManager();

    $inscription = $em->getRepository("OCIMEvenementsBundle:Inscription")->find($idinscription);
    $stagiaire = $inscription->getStagiaire();
    $structure = $stagiaire->getAdresse();
    $evenement = $em->getRepository("OCIMEvenementsBundle:Evenement")->find($idevenement);

    $nbinscription = $inscription->getStagiaire()->getInscription()->count();

    // si le stagiaire de linscription est dans pls inscription, on cherche a supprimer une unique inscription.
    if($nbinscription > 1){
      $personnes = $inscription->getPersonnes();
      foreach($personnes as &$personne){
        $personne->removeInscription($inscription);
        $inscription->removePersonne($personne);
      }
    }

    $intervenant  = new Intervenant();
    $adresse = new Adresse();

    $intervenant->setNom($stagiaire->getNom());
    $intervenant->setCivilite($stagiaire->getCivilite());
    $intervenant->setPrenom($stagiaire->getPrenom());
    $intervenant->setFonction($stagiaire->getFonction());
    $intervenant->setTel($stagiaire->getTel());
    $intervenant->setMail($stagiaire->getMail());

    $adresse->setNomStructure($structure->getNomStructure());
    $adresse->setAdresse($structure->getAdresse());
    $adresse->setAdresseComplement($structure->getAdresseComplement());
    $adresse->setCP($structure->getCP());
    $adresse->setVille($structure->getVille());
    $adresse->setPays($structure->getPays());
    $adresse->setType($structure->getType());

    foreach($structure->getTags() as $tag){
      $adresse->addTag($tag);
    }

    $intervenant->setAdresse($adresse);
    $intervenant->addEvenement($evenement);

    $evenement->addIntervenant($intervenant);

    foreach($stagiaire->getReponsesChampPerso() as $rep){
      //copie des infos logistique que si le modele est applicable aux intervenants
      if(!is_null($rep->getModele()->getEvenement())){
        $_r = new ReponsesChampPerso();
        $_r->setReponse($rep->getReponse());
        $_r->setReponseText($rep->getReponseText());
        $_r->setDate($rep->getDate());
        $_r->setModele($rep->getModele());
        $_r->setPersonne($intervenant);
        $em->persist($_r);
      }

      $em->remove($rep);
    }

    $em->persist($intervenant);
    $em->persist($adresse);
    $em->persist($evenement);

    //suppression
    $em->remove($inscription);

    $em->flush();

    return $this->redirect($this->generateUrl('intervenants_edit', array('id' => $intervenant->getId(), 'idevenement'=> $idevenement)));
  }

  public function updateStatutInscriptionAction(Request $request){
    if($request->isXmlHttpRequest()){
      $data = json_decode($request->getContent());
      $em = $this->getDoctrine()->getManager();
      $idinscription = $data['0']->idinscription;
      $inscription = $em->getRepository('OCIMEvenementsBundle:Inscription')->find($idinscription);
      $inscription->setStatut($data['0']->statut);
      $em->flush();
      return new Response( $inscription->getNumberStatut() , Response::HTTP_OK);
    }
  }

  public function updateStatutFinancementAction(Request $request){
    if($request->isXmlHttpRequest()){
      $data = json_decode($request->getContent());

      $em = $this->getDoctrine()->getManager();

      $idinscription = $data['0']->idinscription;

      $inscription = $em->getRepository('OCIMEvenementsBundle:Inscription')->find($idinscription);

      $inscription->setStatutFinancement($data['0']->statut);

      $em->flush();

      return new Response( $inscription->getStatutFinancement() , Response::HTTP_OK);
    }
  }

  public function updateStatutConventionAction(Request $request){
    if($request->isXmlHttpRequest()){
      $data = json_decode($request->getContent());

      $em = $this->getDoctrine()->getManager();

      $idinscription = $data['0']->idinscription;

      $inscription = $em->getRepository('OCIMEvenementsBundle:Inscription')->find($idinscription);

      $statut = $data['0']->statut;
      $statut = ($statut == "null")? null : (bool)$statut;

      $inscription->setStatutConvention($statut);

      $em->flush();

      $reponse;
      if(is_null($inscription->getStatutConvention())){
        $reponse = "null";
      }
      else{
        $reponse = ($inscription->getStatutConvention() == false )? '0' : '1' ;
      }


      return new Response(  $reponse , Response::HTTP_OK);
    }
  }

  /**
  * Creates a new Inscription entity.
  *
  */
  public function createAction(Request $request, $idevenement)
  {
    $entity = new Inscription();
    $form = $this->createCreateForm($entity, $idevenement);
    $form->handleRequest($request);

    if ($form->isValid()) {
      $em = $this->getDoctrine()->getManager();

      $ordre = $em->getRepository('OCIMEvenementsBundle:Inscription')->getOrdreMaxByEvenement($idevenement);


      $nouvelordre = (!is_null($ordre))? $ordre + 1000 : 0;
      $entity->setOrdre($nouvelordre);

      //exit(\Doctrine\Common\Util\Debug::dump($entity->getSignataire()->getAdresse()->getStructure()));

      $em->persist($entity);

      $em->flush();
      $this->get('session')->getFlashBag()->add('success','Inscription ajoutée.');
      return $this->redirect($this->generateUrl('inscription', array('id' => $entity->getId(), 'idevenement'=> $idevenement)));
    }

    $this->get('session')->getFlashBag()->add('error','Le formulaire contient des erreurs');
    return $this->render('OCIMEvenementsBundle:Inscription:new.html.twig', array(
      'entity' => $entity,
      'form'   => $form->createView(),
      'idevenement' => $idevenement,
    ));
  }

  /**
  * Creates a form to create a Inscription entity.
  *
  * @param Inscription $entity The entity
  *
  * @return \Symfony\Component\Form\Form The form
  */
  private function createCreateForm(Inscription $entity, $idevenement)
  {
    $form = $this->createForm(new InscriptionType($idevenement), $entity, array(
      'action' => $this->generateUrl('inscription_create', array('idevenement'=>$idevenement)),
      'method' => 'POST',
      'em' => $this->getDoctrine()->getManager(),
    ));

    $form->add('submit', 'submit', array('label' => 'Ajouter le stagiaire', 'attr'=> array('class' => 'btn btn-green btn-save')
    ));

    return $form;
  }

  /**
  * Displays a form to create a new Inscription entity.
  *
  */
  public function newAction($idevenement)
  {
    $entity = new Inscription();
    $form   = $this->createCreateForm($entity, $idevenement);
    $em = $this->getDoctrine()->getManager();
    $evenement = $em->getRepository("OCIMEvenementsBundle:Evenement")->find($idevenement);
    $tags = $em->getRepository("OCIMContactsBundle:TagStructure")->findAll();

    return $this->render('OCIMEvenementsBundle:Inscription:new.html.twig', array(
      'entity' => $entity,
      'form'   => $form->createView(),
      'evenement' => $evenement,
      'tags' => $tags,
    ));
  }

  /**
  * Finds and displays a Inscription entity.
  *
  */
  public function showAction(Request $request, $id, $idevenement)
  {
    $type = $request->query->get('type');

    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('OCIMEvenementsBundle:Inscription')->find($id);

    if (!$entity) {
      throw $this->createNotFoundException('Unable to find Inscription entity.');
    }

    $deleteForm = $this->createDeleteForm($id, $idevenement);

    return $this->render('OCIMEvenementsBundle:Inscription:show.html.twig', array(
      'entity'      => $entity,
      'delete_form' => $deleteForm->createView(),
      'idevenement' => $idevenement,
      'ignore_missing' => true,
      'type' => $type,
    ));
  }

  /**
  * Displays a form to edit an existing Inscription entity.
  *
  */
  public function editAction($id, $idevenement)
  {
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('OCIMEvenementsBundle:Inscription')->find($id);
    $tags = $em->getRepository("OCIMContactsBundle:TagStructure")->findAll();

    if (!$entity) {
      throw $this->createNotFoundException('Unable to find Inscription entity.');
    }

    $editForm = $this->createEditForm($entity, $idevenement);
    $deleteForm = $this->createDeleteForm($id, $idevenement);

    return $this->render('OCIMEvenementsBundle:Inscription:edit.html.twig', array(
      'entity'    	=> $entity,
      'form' 			=> $editForm->createView(),
      'delete_form' 	=> $deleteForm->createView(),
      'idevenement' 	=> $idevenement,
      'tags' 	=> $tags,
      //'personne'		=> \Doctrine\Common\Util\Debug::dump($personne),
    ));
  }

  /**
  * Creates a form to edit a Inscription entity.
  *
  * @param Inscription $entity The entity
  *
  * @return \Symfony\Component\Form\Form The form
  */
  private function createEditForm(Inscription $entity, $idevenement)
  {
    $form = $this->createForm(new InscriptionType($idevenement), $entity, array(
      'action' => $this->generateUrl('inscription_update', array('id' => $entity->getId(), 'idevenement'=> $idevenement)),
      'method' => 'PUT',
      'em' => $this->getDoctrine()->getManager(),
    ));

    $form->add('submit', 'submit', array('label' => 'Enregistrer', 'attr'=> array('class'=> 'btn btn-green btn-save'),));

    return $form;
  }
  /**
  * Edits an existing Inscription entity.
  *
  */
  public function updateAction(Request $request, $id, $idevenement)
  {
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('OCIMEvenementsBundle:Inscription')->find($id);

    if (!$entity) {
      throw $this->createNotFoundException('Unable to find Inscription entity.');
    }

    $deleteForm = $this->createDeleteForm($id, $idevenement);
    $editForm = $this->createEditForm($entity, $idevenement);
    $editForm->handleRequest($request);

    if ($editForm->isValid()) {

      $em->flush();
      $this->get('session')->getFlashBag()->add('notice','Modifications sauvegardées');
      return $this->redirect($this->generateUrl('inscription', array('id' => $id, 'idevenement'=>$idevenement)));
    }

    $this->get('session')->getFlashBag()->add('error','Le formulaire contient des erreurs');
    return $this->render('OCIMEvenementsBundle:Inscription:edit.html.twig', array(
      'entity'      => $entity,
      'edit_form'   => $editForm->createView(),
      'delete_form' => $deleteForm->createView(),
      'idevenement' => $idevenement,
    ));
  }
  /**
  * Deletes a Inscription entity.
  *
  */
  public function deleteAction(Request $request, $id, $idevenement)
  {
    $form = $this->createDeleteForm($id, $idevenement);
    $form->handleRequest($request);

    if ($form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $entity = $em->getRepository('OCIMEvenementsBundle:Inscription')->find($id);

      if (!$entity) {
        throw $this->createNotFoundException('Unable to find Inscription entity.');
      }

      // exit(\Doctrine\Common\Util\Debug::dump($entity->getStagiaire()->getInscription()->count()));
      $nbinscription = $entity->getStagiaire()->getInscription()->count();

      // si le stagiaire de linscription est dans pls inscription, on cherche a supprimer une unique inscription.
      if($nbinscription > 1){
        $personnes = $entity->getPersonnes();
        foreach($personnes as &$personne){
          $personne->removeInscription($entity);
          $entity->removePersonne($personne);
        }
      }

      $em->remove($entity);
      $em->flush();
    }
    $this->get('session')->getFlashBag()->add('success','Inscription supprimée.');
    return $this->redirect($this->generateUrl('inscription', array('idevenement'=> $idevenement)) ."#msg-form");
  }

  /**
  * Creates a form to delete a Inscription entity by id.
  *
  * @param mixed $id The entity id
  *
  * @return \Symfony\Component\Form\Form The form
  */
  private function createDeleteForm($id, $idevenement)
  {
    return $this->createFormBuilder()
    ->setAction($this->generateUrl('inscription_delete', array('id' => $id, 'idevenement'=> $idevenement)))
    ->setMethod('DELETE')
    ->add('submit', 'submit', array('label' => ' Supprimer', 'attr'=> array('class'=>'btn btn-red btn-delete') ))
    ->getForm()
    ;
  }
}
