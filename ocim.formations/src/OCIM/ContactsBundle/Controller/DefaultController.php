<?php

namespace OCIM\ContactsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('OCIMContactsBundle:Default:index.html.twig', array('name' => $name));
    }
}
