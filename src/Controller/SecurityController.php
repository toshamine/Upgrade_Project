<?php

namespace App\Controller;

use App\Entity\RDV;
use App\Entity\User1;
use ContainerEZrCWmn\getErrorHandler_ErrorRenderer_HtmlService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         //if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();



        if($this->getUser()){

            return $this->redirectToRoute('index');
        }


        // get the login error if there is one




        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'user' => $this->getUser(),'error' => $error
        , 'alertrdv'=>count($this->getDoctrine()->getManager()->getRepository(RDV::class)->findBy(['Status'=>"Pending"]))
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
