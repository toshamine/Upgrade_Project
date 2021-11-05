<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/clienttest",name="clienttest")
     */
    public function indexclient():Response
    {
        return $this->render("/Client/test.html.twig",[]);
    }

    /**
     * @Route("/admintest",name="admintest")
     */
    public function indexadmin():Response
    {
        return $this->render("/Admin/test.html.twig",[]);
    }

    /**
     * @Route("/hometest",name="hometest")
     */
    public function hometest():Response
    {
        return $this->render("/Client/home.html.twig",[]);
    }

}
