<?php

namespace Oro\Bundle\IssueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('IssueBundle:Default:index.html.twig', array('name' => $name));
    }
}
