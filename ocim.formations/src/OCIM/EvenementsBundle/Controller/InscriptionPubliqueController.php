<?php

namespace OCIM\EvenementsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use OCIM\EvenementsBundle\Entity\Inscription;
use OCIM\EvenementsBundle\Form\InscriptionPubliqueType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints as Assert;

class InscriptionPubliqueController extends Controller
{
    public function inscriptionAction(Request $request)
    {

        $id = $request->query->get('id');

        $entity = $request->query->get('entity');
        $entity = (($entity !== null) && (!empty($entity))) ? $entity : false;

        $inscription = new Inscription();

        $form = $this->createCreateForm($inscription, $id);

        $em = $this->getDoctrine()->getManager();
        $idevenement = $id;

        if ($entity == 'formule') {
            $formule = $em->getRepository("OCIMEvenementsBundle:Formule")->find($id);
            $idevenement = $formule->getEvenementFormule()->get(0)->getEvenement()->getId();
        }

        $evenement = $em->getRepository("OCIMEvenementsBundle:Evenement")->find($idevenement);

        if (!$evenement) {
            throw $this->createNotFoundException(
                "Aucun événement ne correspond à l'id : " . $idevenement
            );
        }

        if ($entity == 'formule') {

            //exit(\Doctrine\Common\Util\Debug::dump($evenementType));
            $form->add('evenements', 'entity', array(
                'mapped' => false,
                'label' => 'Vous participerez aux événements',
                'required' => false,
                'expanded' => true,
                'multiple' => true,
                'constraints' => new Assert\Count(array('min' => 1, 'minMessage' => 'Vous devez choisir au moins un événement',)),
                'property' => 'intitule',
                'class' => 'OCIMEvenementsBundle:Evenement',
                'query_builder' => function (EntityRepository $er) use ($id) {
                    return $er->createQueryBuilder('evenement')
                        ->join('evenement.evenementFormule', 'ff')
                        ->join('ff.formule', 'formule')
                        ->where('formule.id  = :id')
                        ->setParameter('id', $id);
                }
            ));

            $form->add('evenementformule', 'hidden', array(
                'mapped' => false,
                'data' => 'type',
            ));
            $form->add('typeid', 'hidden', array(
                'mapped' => false,
                'data' => $id,
            ));
        }

        return $this->render('OCIMEvenementsBundle:InscriptionPublique:form-public.html.twig', array(
            'form' => $form->createView(),
            'id' => $id
        ));
    }

    public function createInscriptionAction(Request $request)
    {
        $partdonnees = $request->request->get('ocim_evenementsbundle_inscription');

        $idff = $partdonnees['evenementformule'];

        if ($idff == 'type') {
            $idevenement = $partdonnees['evenements'][0];
        } else {
            $idevenement = $this->getDoctrine()->getManager()->getRepository('OCIMEvenementsBundle:evenementFormule')->find($idff)->getEvenement()->getId();
        }

        $entity = new Inscription();

        $form = $this->createCreateForm($entity, $idevenement);

        if ($idff == 'type') {
            $typeid = $partdonnees['typeid'];
            $form->add('evenements', 'entity', array(
                'mapped' => false,
                'label' => 'Vous participerez aux évènements',
                'required' => false,
                'expanded' => true,
                'multiple' => true,
                'constraints' => new Assert\Count(array('min' => 1, 'minMessage' => 'Vous devez choisir au moins un evenement',)),
                'property' => 'intitule',
                'class' => 'OCIMEvenementsBundle:Evenement',
                'query_builder' => function (EntityRepository $er) use ($typeid) {
                    return $er->createQueryBuilder('evenement')
                        ->join('evenement.evenementFormule', 'ff')
                        ->join('ff.formule', 'formule')
                        ->where('formule.id  = :id')
                        ->setParameter('id', $typeid);
                }
            ));
            $form->add('evenementformule', 'hidden', array(
                'mapped' => false,
                'data' => 'type',
            ));
            $form->add('typeid', 'hidden', array(
                'mapped' => false,
                'data' => $typeid,
            ));
        }


        $form->handleRequest($request);
        // exit(\Doctrine\Common\Util\Debug::dump($form->isValid()));

        // formulaire valide
        if ($form->isValid()) {


            //FORMULAIRE SPECIAL
            if ($idff == "type" && is_array($partdonnees['evenements'])) {
                $em = $this->getDoctrine()->getManager();
                $evenements = $em->getRepository('OCIMEvenementsBundle:Evenement')->findById($partdonnees['evenements']);

                foreach ($evenements as $evenement) {


                    $inscription = new Inscription();

                    $inscription->setStagiaire($entity->getStagiaire());
                    $inscription->setAdmin($entity->getAdmin());

                    $ffarray = $evenement->getEvenementFormule();
                    $ff = $ffarray[0];
                    $em = $this->getDoctrine()->getManager();

                    $ordre = $em->getRepository('OCIMEvenementsBundle:Inscription')->getOrdreMaxByEvenement($evenement->getId());

                    $nouvelordre = (!is_null($ordre)) ? $ordre + 1000 : 0;

                    $inscription->setOrdre($nouvelordre);

                    $inscription->setAttentes($entity->getAttentes());

                    $inscription->setStatut(2);

                    $inscription->setEvenementformule($ff);


                    $em->persist($inscription);

                    $em->flush();


                }
                $inscription_success = true;
                try {
                    $em->flush();
                } catch (Exception $e) {
                    $inscription_success = false;
                }

                return $this->render('OCIMEvenementsBundle:InscriptionPublique:confirmation.html.twig', array(
                    //'success_mail' => $mail_success,
                    'success_inscription' => $inscription_success,
                ));


            } //FORMULAIRE FORMATION
            else {
                $em = $this->getDoctrine()->getManager();
                $ordre = $em->getRepository('OCIMEvenementsBundle:Inscription')->getOrdreMaxByEvenement($idevenement);
                $nouvelordre = (!is_null($ordre)) ? $ordre + 1000 : 0;
                $entity->setOrdre($nouvelordre);
                $entity->setStatut(2);
                $em->persist($entity);
                // bool pour vérification du bon déroulement de l'inscription
                $inscription_success = true;
                $mail_success = true;
                try {
                    $em->flush();
                } catch (Exception $e) {
                    $inscription_success = false;
                }
                // on envoit le mail que si l'inscription a réussi
                if ($inscription_success) {
                    // envoi de mails
                    // Create the Transport
                    $transport = \Swift_SmtpTransport::newInstance('smtp.u-bourgogne.fr', 25, 'tls');

                    // Create the Mailer using your created Transport
                    $mailer = \Swift_Mailer::newInstance($transport);
                    $message = \Swift_Message::newInstance()
                        ->setSubject('[OCIM] Inscription à la formation : ' . $entity->getEvenementFormule()->getEvenement()->getIntitule())
                        ->setFrom('formation.ocim@u-bourgogne.fr')
                        ->setBcc('formation.ocim@u-bourgogne.fr')
                        ->setContentType("text/html")
                        ->setTo($entity->getStagiaire()->getMail())
                        ->setBody($this->renderView('OCIMEvenementsBundle:InscriptionPublique:email-inscription.html.twig', array('inscription' => $entity)))//->setCharset('utf-8')
                    ;
                    if (!$mailer->send($message)) {
                        $mail_success = false;
                    }
                }
                return $this->render('OCIMEvenementsBundle:InscriptionPublique:confirmation.html.twig', array(
                    'success_mail' => $mail_success,
                    'success_inscription' => $inscription_success
                ));

            }


        } else {
            return $this->render('OCIMEvenementsBundle:InscriptionPublique:form-public.html.twig', array(
                'form' => $form->createView(),
                'idevenement' => $idevenement
            ));
        }
    }


    private function createCreateForm(Inscription $entity, $idevenement)
    {
        $form = $this->createForm(new InscriptionPubliqueType($idevenement), $entity, array(
            'method' => 'POST',
            'em' => $this->getDoctrine()->getManager(),
        ));

        $form->add('submit', 'submit', array('label' => "Valider"));

        return $form;
    }


}
