<?php

namespace Librinfo\BaseEntitiesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('LibrinfoBaseEntitiesBundle:Default:index.html.twig', array('name' => $name));
    }
}
