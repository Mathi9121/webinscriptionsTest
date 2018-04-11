<?php

namespace OCIM\ExportBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use OCIM\ExportBundle\Entity\Template;
use OCIM\ExportBundle\Form\TemplateType;

/**
 * Template controller.
 *
 */
class TemplateController extends Controller
{

    /**
     * Lists all Template entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('OCIMExportBundle:Template')->findBy( array(), array('ordre'=> "ASC"));

        return $this->render('OCIMExportBundle:Template:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    public function majOrdreAction(Request $request){
      // test ajax
      if($request->isXmlHttpRequest()){
        //recuperation du tableau
        $order = $request->getContent();
        $order = json_decode($order);

        $ids = array_map( function($c){ return $c->id; } , $order);

        $em = $this->getDoctrine()->getManager();
        $templates = $em->getRepository('OCIMExportBundle:Template')->findById($ids);

        foreach($templates as &$obj){
          $obj->setOrdre((array_search($obj->getId(), $ids)+1));
        }

        $em->flush();

        return new Response();

      }
    }

    /**
     * Creates a new Template entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Template();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success','Gabarit Créé.');
            return $this->redirect($this->generateUrl('documents'));
        }
        $this->get('session')->getFlashBag()->add('error','Le formulaire contient des erreurs.');
        return $this->render('OCIMExportBundle:Template:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    public function liensAction(Request $request){
      if($request->isXmlHttpRequest()){
        $idinscription = $request->getContent();
        $em = $this->getDoctrine()->getManager();
        $liens = $em->getRepository('OCIMExportBundle:Template')->findBy( array(), array('ordre'=> "ASC"));

        //$router = $this->get('router');

        $reponse = array();

        foreach($liens as $lien){
          $lien->_nom = $lien->getNom();
          $lien->_preview = $this->generateUrl('documents_show', array('id' => $lien->getId(), 'idinscription'=> $idinscription, 'mode'=>'preview'), true);
          $lien->_url = $this->generateUrl('documents_show', array('id' => $lien->getId(), 'idinscription'=> $idinscription, 'mode'=>'show'), true);
          $reponse[] = $lien;
        }

        //return new Response( , 200);
        return new Response( json_encode($reponse), Response::HTTP_OK);
      }
    }

    public function liensConventionAction(Request $request){
      if($request->isXmlHttpRequest()){
        $idinscription = $request->getContent();
        $em = $this->getDoctrine()->getManager();
        $liens = $em->getRepository('OCIMExportBundle:Template')->findBy(array('type' => "convention"));

        //$router = $this->get('router');

        $reponse = array();

        foreach($liens as $lien){
          $lien->_nom = $lien->getNom();
          $lien->_url = $this->generateUrl('documents_show', array('id' => $lien->getId(), 'idinscription'=> $idinscription, 'mode'=>'show'), true);
          $lien->_preview = $this->generateUrl('documents_show', array('id' => $lien->getId(), 'idinscription'=> $idinscription, 'mode'=>'preview'), true);
          $reponse[] = $lien;
        }

        //return new Response( , 200);
        return new Response( json_encode($reponse), Response::HTTP_OK);
      }
    }

    /**
     * Creates a form to create a Template entity.
     *
     * @param Template $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Template $entity)
    {
        $form = $this->createForm(new TemplateType(), $entity, array(
            'action' => $this->generateUrl('documents_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class'=>'btn btn-green btn-save')));

        return $form;
    }

    /**
     * Displays a form to create a new Template entity.
     *
     */
    public function newAction()
    {
        $entity = new Template();
        $form   = $this->createCreateForm($entity);

        return $this->render('OCIMExportBundle:Template:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Template entity.
     *
     */
    public function showAction($id, $idinscription, $mode)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OCIMExportBundle:Template')->find($id);

        $inscription = $em->getRepository('OCIMEvenementsBundle:Inscription')->find($idinscription);
		    $evenement = $em->getRepository('OCIMEvenementsBundle:Evenement')->find($inscription->getEvenementFormule()->getEvenement()->getId());

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Template entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

		// Nom du fichier
		$filename = $entity->getFilename();
    if(is_null($filename)){
      $filename = $entity->getNom();

    }
    else{
      $filename = str_replace('{{evenement.intitule}}', $evenement->getIntitule(), $filename);
      $filename = str_replace('{{evenement.id}}', $evenement->getId(), $filename);
      $filename = str_replace('{{inscription.stagiaire.nom}}', $inscription->getStagiaire()->getNom(), $filename);
      $filename = str_replace('{{inscription.stagiaire.prenom}}', $inscription->getStagiaire()->getPrenom(), $filename);

      if(!is_null($inscription->getConvention())){
        $filename = str_replace('{{inscription.convention.numeroToString}}', $inscription->getConvention()->getNumeroToString(), $filename);
      }
    }
		// Ajout de la fonction twig pour calculer la durée entre deux date
		$env = new \Twig_Environment(new \Twig_Loader_String());
		$function = new \Twig_SimpleFunction('date_difference', function ($start, $end) {
			return $start->diff($end, true)->format('%a') + 1;
		});


      $mois = array('01'=>'janvier','02'=>'février','03'=>'mars','04'=>'avril','05'=>'mai','06'=>'juin','07'=>'juillet','08'=>'août','09'=>'septembre','10'=>'octobre','11'=>'novembre','12'=>'décembre');
      $date = new \DateTime('now');
      $str_date = $date->format('d ').$mois[$date->format('m')].$date->format(' Y');
      $date_abbr = $date->format('d/m/Y');


		// ajout à l'environnement
		$env->addFunction($function);

		// $contenu = "<!DOCTYPE html><html><head><meta charset='utf-8'/>
		//			</head><body style='margin:0px'>".$entity->getContenu()."</body></html>";
		$contenu = $entity->getContenu();
    $contenu = "<style>.pagebreak{page-break-after: always;} @media print{ .pagebreak{height:0px; border:0;} }</style>".$contenu;
		// contenu et valeurs
		$contenu = $env->render(
			$contenu,
			array("evenement" => $evenement, "inscription" => $inscription, 'date'=> $str_date, 'date_abbr'=>$date_abbr)
		);

    //test du mode : show ou preview
    if($mode == 'preview'){
      return $this->render('OCIMExportBundle:Template:preview.html.twig', array(
        'contenu' => $contenu,
        'filename' => $filename,
      ));
    }
    else{
      return new Response(
        $this->get('knp_snappy.pdf')->getOutputFromHtml($contenu),
        200,
        array(
          'Content-Type'          => 'application/pdf',
          'Content-Disposition'   => 'attachment; filename="'.$filename.'.pdf"'
        )
      );
    }

    }

    public function exportfrompreviewAction(Request $request){
      $content = $request->request->get('content');
      $filename = $request->request->get('filename');

      $content = "<style>.pagebreak{height: 1px; page-break-after: always;} @media print{ .pagebreak{height:0px; border:0;} }</style>".$content;
      return new Response(
        $this->get('knp_snappy.pdf')->getOutputFromHtml($content),
        200,
        array(
          'Content-Type'          => 'application/pdf',
          'Content-Disposition'   => 'attachment; filename="'.$filename.'.pdf"'
        )
      );
    }

    /**
     * Displays a form to edit an existing Template entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OCIMExportBundle:Template')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Template entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OCIMExportBundle:Template:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Template entity.
    *
    * @param Template $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Template $entity)
    {
        $form = $this->createForm(new TemplateType(), $entity, array(
            'action' => $this->generateUrl('documents_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Enregistrer', 'attr' => array('class'=>'btn btn-green btn-save')));

        return $form;
    }
    /**
     * Edits an existing Template entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OCIMExportBundle:Template')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Template entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add('success','Modifications enregistrées.');
            return $this->redirect($this->generateUrl('documents_edit', array('id' => $id)));
        }

        $this->get('session')->getFlashBag()->add('error','Erreur pendant la sauvegarde.');
        return $this->render('OCIMExportBundle:Template:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Template entity.
     *
     */
    public function deleteAction( $id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OCIMExportBundle:Template')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Template entity.');
        }

        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success','Gabarit supprimé.');
        return $this->redirect($this->generateUrl('documents'));
    }

    /**
     * Creates a form to delete a Template entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('documents_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array('class'=>'btn btn-red btn-delete')))
            ->getForm()
        ;
    }

    public function copieAction(Request $request){
      if($request->isXmlHttpRequest()){
        $data = json_decode($request->getContent());
        $nom = $data->nom;
        $id = $data->id;

        $copie = new Template();

        $em = $this->getDoctrine()->getManager();
        $template = $em->getRepository('OCIMExportBundle:Template')->find($id);

        $copie->setNom($nom);
        $copie->setFilename($template->getFilename());
        $copie->setContenu($template->getContenu());
        $copie->setType($template->getType());

        $em->persist($copie);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success','Gabarit "'.$template->getNom().'" copié.');
        return new Response( json_encode($copie->getId()), Response::HTTP_OK);
      }
    }
}
