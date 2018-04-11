<?php

namespace OCIM\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class AdminController extends Controller
{
    public function connexionAction()
    {
		$request = $this->getRequest();
		$session = $request->getSession();

		//On regarde les erreurs
		if($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)){
			$error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
		}
		else{
			$error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
			$session->remove(SecurityContext::AUTHENTICATION_ERROR);
		}
		return $this->render("OCIMUserBundle:Admin:connexion.html.twig", 
			array(
			'last_username' => $session->get(SecurityContext::LAST_USERNAME),
			'error'=> $error
			));
    }
}
