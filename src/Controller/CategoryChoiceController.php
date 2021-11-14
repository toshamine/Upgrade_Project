<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryChoiceController extends AbstractController
{
    /**
     * @Route ("/choice",name="choice")
     */
    public function catchoice():Response
    {
        return $this->render("white_test/categorychoice.html.twig",[]);
    }
}
