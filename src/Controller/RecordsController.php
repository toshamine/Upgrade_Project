<?php

namespace App\Controller;

use App\Entity\Records;
use App\Form\RecordsType;
use App\Repository\RecordsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/records')]
class RecordsController extends AbstractController
{
    #[Route('/', name: 'records_index', methods: ['GET'])]
    public function index(RecordsRepository $recordsRepository): Response
    {
        return $this->render('records/index.html.twig', [
            'records' => $recordsRepository->findAll()
        ]);
    }

    #[Route('/new', name: 'records_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        $record = new Records();
        $form = $this->createForm(RecordsType::class, $record);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($record);
            $entityManager->flush();

            return $this->redirectToRoute('records_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('records/new.html.twig', [
            'record' => $record,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'records_show', methods: ['GET'])]
    public function show(Records $record): Response
    {
        return $this->render('records/show.html.twig', [
            'record' => $record,
        ]);
    }

    #[Route('/{id}/edit', name: 'records_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Records $record): Response
    {
        $form = $this->createForm(RecordsType::class, $record);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('records_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('records/edit.html.twig', [
            'record' => $record,
            'form' => $form,
        ]);
    }

    /**
     * @Route ("/deleterec/{id}",name="deleterec")
     */
   public function deleterecord(int $id):Response
   {
       $em=$this->getDoctrine()->getManager();
       $record=$em->getRepository(Records::class)->find($id);
       $em->remove($record);
       $em->flush();
       return $this->redirectToRoute('records_index');
   }
}
