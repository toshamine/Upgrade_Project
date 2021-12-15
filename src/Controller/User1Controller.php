<?php

namespace App\Controller;

use App\Entity\RDV;
use App\Entity\User1;
use App\Form\ChangePasswordFormType;
use App\Form\User1Type;
use App\Repository\User1Repository;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

#[Route('/user1')]
class User1Controller extends AbstractController
{
    private EmailVerifier $emailVerifier;

    use ResetPasswordControllerTrait;

    private $resetPasswordHelper;
    private $userPasswordEncoder;

    public function __construct(ResetPasswordHelperInterface $resetPasswordHelper,EmailVerifier $emailVerifier)
    {
        $this->resetPasswordHelper = $resetPasswordHelper;
        $this->emailVerifier = $emailVerifier;
    }


    /**
     * @Route("/myprofile",name="profile")
     */
    public function profile():Response
    {

        // $user1 = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();
        $user1 = $entityManager->getRepository(User1::class)->findOneBy(['email'=>$this->getUser()->getUserIdentifier()]);
        $picture = $this->getParameter("app.path.product_images").'/'.$user1->getPicture();
        return $this->render('user1/show.html.twig', [
            'user1' => $this->getUser(),'user' => $this->getUser(),
            'pic'=>$picture,
             'alertrdv'=>count($this->getDoctrine()->getManager()->getRepository(RDV::class)->findBy(['Status'=>"Pending"]))
        ]);
    }


    /**
     * @Route("/{id}",name="user1_show")
     */
    public function show(User1 $user1):Response
    {

        // $user1 = $this->getUser();
        $picture = $this->getParameter("app.path.product_images").'/'.$user1->getPicture();
        return $this->render('user1/show.html.twig', [
            'user1' => $user1,'user' => $this->getUser(),
            'pic'=>$picture,
            'alertrdv'=>count($this->getDoctrine()->getManager()->getRepository(RDV::class)->findBy(['Status'=>"Pending"]))
        ]);
    }





    #[Route('/{id}/edit', name: 'user1_edit', methods: ['GET','POST'])]
    public function edit(Request $request, User1 $user1,UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User1::class)->findOneBy(['email'=>$this->getUser()->getUserIdentifier()]);
        $picture = $this->getParameter("app.path.product_images").'/'.$user->getPicture();

        $form = $this->createForm(User1Type::class, $user1);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            if ($user->getPassword() !== null) {
                $encodedPassword = $userPasswordHasherInterface->hashPassword(
                    $user,
                    $form->get('Password')->getData()
                );
                $user->setPassword($encodedPassword,
                    $user
                );
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user1/edit.html.twig', [
            'user1' => $user1,
            'form' => $form,
            'user'=>$this->getUser(),
            'pic'=>$picture,
            'alertrdv'=>count($this->getDoctrine()->getManager()->getRepository(RDV::class)->findBy(['Status'=>"Pending"]))
        ]);
    }

    #[Route('/{id}', name: 'user1_delete', methods: ['POST'])]
    public function delete(Request $request, User1 $user1): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user1->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user1);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user1_index', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/block/user/{id}",name="blockuser")
     */
    public function block($id):Response
    {
        $em=$this->getDoctrine()->getManager();
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $em->getRepository(User1::class)->find($id),
            (new TemplatedEmail())
                ->from(new Address('Upgrade@gmail.com', 'UpgradeBot'))
                ->to($em->getRepository(User1::class)->find($id)->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/blockmail.html.twig')
        );


        $user=new User1();
        $user=$em->getRepository(User1::class)->find($id);
        $user->setIsVerified(false);
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('user1_show',['id'=>$id]);
    }

    /**
     * @Route("/unblock/us/er/{id}",name="unblockuser")
     */
    public function unblock($id):Response
    {
        $em=$this->getDoctrine()->getManager();
        $user=new User1();
        $user=$em->getRepository(User1::class)->find($id);
        $user->setIsVerified(true);
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('user1_show',['id'=>$id]);
    }
}
