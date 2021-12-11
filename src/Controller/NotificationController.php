<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Form\NotificationType;
use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/notification')]
class NotificationController extends AbstractController
{
    #[Route('/', name: 'notification_index', methods: ['GET'])]
    public function index(NotificationRepository $notificationRepository): Response
    {
        return $this->render('notification/index.html.twig', [
            'notifications' => $notificationRepository->findByUser($this->getUser()),
            'user'=>$this->getUser()
        ]);
    }

    #[Route('/{id}', name: 'notification_show', methods: ['GET'])]
    public function show(Notification $notification): Response
    {
        $notification->setOpened(true);
        $em=$this->getDoctrine()->getManager();
        $em->persist($notification);
        $em->flush();
        return $this->render('notification/show.html.twig', [
            'notification' => $notification,
            'user'=>$this->getUser()
        ]);
    }



    /**
     * @Route("deletenotif/{id}" ,name="notification_delete")
     */
    public function delete($id): Response
    {

        $notif=$this->getDoctrine()->getRepository(Notification::class)->find($id);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($notif);
            $entityManager->flush();

        return $this->redirectToRoute('notification_index');
    }

    /**
     * @Route ("/deleteall/notif",name="deleteallnotif")
     */
    public function deleteall(): Response
    {
        $em=$this->getDoctrine()->getManager();
        $notif=$em->getRepository(Notification::class)->findByUser($this->getUser());
        $i=0;
        while($i!=count($notif)){
            $em->remove($notif[$i]);
            $em->flush();
            $i++;
        }
        return $this->redirectToRoute('notification_index',['user'=>$this->getuser()]);
    }
}
