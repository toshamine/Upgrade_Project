<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Notification;
use App\Entity\RDV;
use App\Entity\User1;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/contact')]
class ContactController extends AbstractController
{
    #[Route('/', name: 'contact_index', methods: ['GET'])]
    public function index(ContactRepository $contactRepository): Response
    {
        return $this->render('contact/index.html.twig', [
            'contacts' => $contactRepository->findAll(),
            'alertrdv'=>count($this->getDoctrine()->getManager()->getRepository(RDV::class)->findBy(['Status'=>"Pending"]))
        ]);
    }

    #[Route('/new', name: 'contact_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        $contact->setDate(new \DateTime('now'));
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $contact->setUser($this->getUser());
            $entityManager->persist($contact);
            $entityManager->flush();
            $notification=new Notification();
            $notification->setOpened(false);
            $notification->setUser($entityManager->getRepository(User1::class)->find(11));
            $notification->setDate(new \DateTime('now'));
            $notification->setText($this->getUser()." Sent You A Message : ".$contact->getText());
            $entityManager->persist($notification);
            $entityManager->flush();

            return $this->redirectToRoute('contact_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contact/new.html.twig', [
            'contact' => $contact,
            'form' => $form,
            'user'=>$this->getUser(), 'alertrdv'=>count($this->getDoctrine()->getManager()->getRepository(RDV::class)->findBy(['Status'=>"Pending"]))

        ]);
    }

    #[Route('/{id}', name: 'contact_show', methods: ['GET'])]
    public function show(Contact $contact): Response
    {
        return $this->render('contact/show.html.twig', [
            'contact' => $contact,
            'alertrdv'=>count($this->getDoctrine()->getManager()->getRepository(RDV::class)->findBy(['Status'=>"Pending"]))
        ]);
    }

    #[Route('/{id}/edit', name: 'contact_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Contact $contact): Response
    {
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('contact_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contact/edit.html.twig', [
            'contact' => $contact,
            'form' => $form,
            'alertrdv'=>count($this->getDoctrine()->getManager()->getRepository(RDV::class)->findBy(['Status'=>"Pending"]))
        ]);
    }

    #[Route('/{id}', name: 'contact_delete', methods: ['POST'])]
    public function delete(Request $request, Contact $contact): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contact->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contact);
            $entityManager->flush();
        }

        return $this->redirectToRoute('contact_index', [], Response::HTTP_SEE_OTHER);
    }
}
