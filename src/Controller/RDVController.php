<?php

namespace App\Controller;

use App\Entity\RDV;
use App\Form\RDVType;
use App\Repository\RDVRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/r/d/v')]
class RDVController extends AbstractController
{
    #[Route('/', name: 'r_d_v_index', methods: ['GET'])]
    public function index(RDVRepository $rDVRepository): Response
    {
        return $this->render('rdv/index.html.twig', [
            'user'=>$this->getUser(),
            'r_d_vs' => $rDVRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'r_d_v_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        $rDV = new RDV();
        $form = $this->createForm(RDVType::class, $rDV);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $rDV->setStatus("Pending");
            $rDV->setUser($this->getUser());
            $entityManager->persist($rDV);
            $entityManager->flush();

            return $this->redirectToRoute('r_d_v_index', ['user'=>$this->getUser()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rdv/new.html.twig', [
            'user'=>$this->getUser(),
            'r_d_v' => $rDV,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'r_d_v_show', methods: ['GET'])]
    public function show(RDV $rDV): Response
    {
        return $this->render('rdv/show.html.twig', [
            'user'=>$this->getUser(),
            'r_d_v' => $rDV,
        ]);
    }

    #[Route('/{id}/edit', name: 'r_d_v_edit', methods: ['GET','POST'])]
    public function edit(Request $request, RDV $rDV): Response
    {
        $form = $this->createForm(RDVType::class, $rDV);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('r_d_v_index', ['user'=>$this->getUser()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rdv/edit.html.twig', [
            'user'=>$this->getUser(),
            'r_d_v' => $rDV,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'r_d_v_delete', methods: ['POST'])]
    public function delete(Request $request, RDV $rDV): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rDV->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($rDV);
            $entityManager->flush();
        }

        return $this->redirectToRoute('r_d_v_index', ['user'=>$this->getUser()], Response::HTTP_SEE_OTHER);
    }
}