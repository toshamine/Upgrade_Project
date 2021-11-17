<?php

namespace App\Controller;

use App\Entity\Document;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DocumentController extends AbstractController
{
    /**
     * @Route("/document", name="document_index")
     */
    public function index(DocumentRepository $documentRepository): Response
    {
        return $this->render('document/index.html.twig', [
            'documents' => $documentRepository->findAll(),
        ]);
    }

   // #[Route('/new', name: 'document_new')]

    /**
     * @Route("/new", name="document_new")
     */
    public function new(Request $request): Response
    {
        $document = new Document();


        $form = $this->createForm(DocumentType::class, $document);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $document =$form['File']->getData();

            $fichier=md5(uniqid()).'.'.$document->guessExtension();
            $document->move(
                $this->getParameter('images_directory'),
                $fichier

            );


            $document->setFile($fichier);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($document);
            $entityManager->flush();

            return $this->redirectToRoute('document_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('document/new.html.twig', [
            'document' => $document,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'document_show', methods: ['GET'])]
    public function show(Document $document): Response
    {
        return $this->render('document/show.html.twig', [
            'document' => $document,
        ]);
    }

    #[Route('/{id}/edit', name: 'document_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Document $document): Response
    {
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('document_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('document/edit.html.twig', [
            'document' => $document,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'document_delete', methods: ['POST'])]
    public function delete(Request $request, Document $document): Response
    {
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($document);
            $entityManager->flush();
        }

        return $this->redirectToRoute('document_index', [], Response::HTTP_SEE_OTHER);
    }
}
