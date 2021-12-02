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
        ]);
    }





    #[Route('/{id}/edit', name: 'user1_edit', methods: ['GET','POST'])]
    public function edit(Request $request, User1 $user1): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User1::class)->findOneBy(['email'=>$this->getUser()->getUserIdentifier()]);
        $picture = $this->getParameter("app.path.product_images").'/'.$user->getPicture();

        $form = $this->createForm(User1Type::class, $user1);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user1/edit.html.twig', [
            'user1' => $user1,
            'form' => $form,
            'user'=>$this->getUser(),
            'pic'=>$picture
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
