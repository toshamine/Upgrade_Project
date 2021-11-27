<?php

namespace App\Controller;

use App\Entity\User1;
use App\Form\User1Type;
use App\Repository\User1Repository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user1')]
class User1Controller extends AbstractController
{
    #[Route('/', name: 'user1_index', methods: ['GET'])]
    public function index(User1Repository $user1Repository): Response
    {
        if($this->getUser()){

            $entityManager = $this->getDoctrine()->getManager();
            $userV = $entityManager->getRepository(User1::class)->findOneBy(['email'=>$this->getUser()->getUserIdentifier(),
                'isVerified'=>false]);

            if ($userV)
            {
                return $this->redirectToRoute('app_logout');
            }


            return $this->render("user1/index.html.twig",['user'=>$this->getUser()
                ,'user1s' => $user1Repository->findAll(),]);
        }


        return $this->render('user1/index.html.twig', [
            'user1s' => $user1Repository->findAll(),
        ]);
    }

    #[Route('/new', name: 'user1_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        $user1 = new User1();
        $form = $this->createForm(User1Type::class, $user1);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user1);
            $entityManager->flush();

            return $this->redirectToRoute('user1_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user1/new.html.twig', [
            'user1' => $user1,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'user1_show', methods: ['GET'])]
    public function show(User1 $user1): Response
    {
        return $this->render('user1/show.html.twig', [
            'user1' => $user1,
        ]);
    }

    #[Route('/{id}/edit', name: 'user1_edit', methods: ['GET','POST'])]
    public function edit(Request $request, User1 $user1): Response
    {
        $form = $this->createForm(User1Type::class, $user1);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user1_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user1/edit.html.twig', [
            'user1' => $user1,
            'form' => $form,
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
}
