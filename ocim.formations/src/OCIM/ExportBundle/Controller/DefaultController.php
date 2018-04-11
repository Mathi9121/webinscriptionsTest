<?php

namespace OCIM\ExportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('OCIMExportBundle:Default:index.html.twig', array('name' => $name));
    }
}
