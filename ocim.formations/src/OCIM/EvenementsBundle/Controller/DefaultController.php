<?php

namespace OCIM\EvenementsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Evenement controller.
 *
 */
class DefaultController extends Controller
{
	public function indexAction(){

		$em = $this->getDoctrine()->getManager();

        $evenements = $em->getRepository('OCIMEvenementsBundle:Evenement')->findAllFutursEvents();
        $formations = $em->getRepository('OCIMEvenementsBundle:Evenement')->findAllFutursFormations();
        $inscriptions = $em->getRepository('OCIMEvenementsBundle:Inscription')->lastInscriptions();

		//COUNT
		$qb = $em->createQueryBuilder();
		$qb->select('count(f.id)');
		$qb->from('OCIMEvenementsBundle:Event','f');
		$countEvents = $qb->getQuery()->getSingleScalarResult();

		$qb = $em->createQueryBuilder();
		$qb->select('count(f.id)');
		$qb->from('OCIMEvenementsBundle:Formation','f');
		$countFormations = $qb->getQuery()->getSingleScalarResult();

		$qb = $em->createQueryBuilder();
		$qb->select('count(i.id)');
		$qb->from('OCIMEvenementsBundle:Inscription','i');
		$countInscriptions = $qb->getQuery()->getSingleScalarResult();

		$qb = $em->createQueryBuilder();
		$qb->select('count(f.id)');
		$qb->from('OCIMEvenementsBundle:Formule','f');
		$countFormules = $qb->getQuery()->getSingleScalarResult();

		$qb = $em->createQueryBuilder();
		$qb->select('count(i.id)');
		$qb->from('OCIMContactsBundle:Intervenant','i');
		$countIntervenant = $qb->getQuery()->getSingleScalarResult();

		$qb = $em->createQueryBuilder();
		$qb->select('count(c.id)');
		$qb->from('OCIMEvenementsBundle:Convention','c');
		$countConvention = $qb->getQuery()->getSingleScalarResult();

		foreach($evenements as $evenement){
			$evenement->_count = $em->getRepository('OCIMEvenementsBundle:Inscription')->countInscriptionsByEvenement($evenement->getId());
		}

		foreach($formations as $formation){
			$formation->_count = $em->getRepository('OCIMEvenementsBundle:Inscription')->countInscriptionsByEvenement($formation->getId());
		}

		return $this->render('OCIMEvenementsBundle:Default:index.html.twig', array(
			'evenements'=> $evenements,
			'formations'=> $formations,
			'inscriptions'=> $inscriptions,
			'countEvents' => $countEvents,
			'countFormations' => $countFormations,
			'countInscriptions' => $countInscriptions,
			'countIntervenant' => $countIntervenant,
			'countConvention' => $countConvention
		));
	}
}
