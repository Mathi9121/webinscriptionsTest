<?php

namespace OCIM\UserBundle\Controller;

/**
 * User controller.
 *
 */
class UserController extends Controller
{

    /**
     * Affichage.
     *
     */
    public function indexAction()
    {
        //$em = $this->getDoctrine()->getManager();

        //$entities = $em->getRepository('OCIMUserBundle:User')->findAll();

        return $this->render('OCIMUserBundle:User:hello.html.twig', array());
    }
}
