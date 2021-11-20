<?php

namespace App\Controller;

use App\Entity\User1;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{


    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/",name="home")
     */
    public function home():Response
    {

        return $this->redirectToRoute('app_login');
    }
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

    /**
     * @Route("/base",name="base")
     */
    public function basetest():Response
    {
        $user = $this->getUser();
        return $this->render("base.html.twig",['user'=>$user]);
    }


    /**
     * @Route("/index",name="index")
     */
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {


        /* if ($this->isGranted('ROLE_ADMIN')){
                        echo 'yess';die;
                    }*/





        if($this->getUser()){

            $entityManager = $this->getDoctrine()->getManager();
            $userV = $entityManager->getRepository(User1::class)->findOneBy(['email'=>$this->getUser()->getUserIdentifier(),
                'isVerified'=>false]);

                if ($userV)
                {
                    return $this->redirectToRoute('app_logout');
                }


            return $this->render("/test/index.html.twig",['user'=>$this->getUser()]);
        }else{


            $user = new User1();
            $form = $this->createForm(RegistrationFormType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // encode the plain password
                $user->setPassword(
                    $userPasswordHasherInterface->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                // generate a signed url and email it to the user
                $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                    (new TemplatedEmail())
                        ->from(new Address('Upgrade@gmail.com', 'UpgradeBot'))
                        ->to($user->getEmail())
                        ->subject('Please Confirm your Email')
                        ->htmlTemplate('registration/confirmation_email.html.twig')
                );
                // do anything else you need here, like send an email

                return $this->redirectToRoute('app_login');
            }

            return $this->render('registration/register.html.twig', [
                'registrationForm' => $form->createView(),
            ]);

        }

    }

}
