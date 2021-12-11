<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\RDV;
use App\Entity\User1;
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

        if($this->getUser()){
            $entityManager = $this->getDoctrine()->getManager();
            $userV = $entityManager->getRepository(User1::class)->findOneBy(['email'=>$this->getUser()->getUserIdentifier(),
                'isVerified'=>false]);


            if(!$this->isGranted('ROLE_ADMIN')){
                return $this->redirectToRoute('index');
            }

        }else{
            return $this->redirectToRoute('index');
        }


        $picture = $this->getParameter("app.path.product_images").'/' ;
        return $this->render('rdv/index.html.twig', [
            'user'=>$this->getUser(),
            'r_d_vs' => array_reverse($rDVRepository->findBy(['Status'=>'Pending'])),
            'picture'=>$picture
        ]);
    }

    /**
     * @Route("/allrdv",name="allrdv")
     */
    public function allrdv():Response
    {
        $picture = $this->getParameter("app.path.product_images").'/' ;
        return $this->render('rdv/index.html.twig', [
            'user'=>$this->getUser(),
            'r_d_vs' => array_reverse($this->getDoctrine()->getManager()->getRepository(RDV::class)->findByOrder()),
            'picture'=>$picture
        ]);
    }

    /**
     * @Route("/acceptedrdv",name="acceptedrdv")
     */
    public function acceptedrdv():Response
    {
        $picture = $this->getParameter("app.path.product_images").'/' ;
        return $this->render('rdv/index.html.twig', [
            'user'=>$this->getUser(),
            'r_d_vs' => array_reverse($this->getDoctrine()->getManager()->getRepository(RDV::class)->findBy(['Status'=>'Accepted'])),
            'picture'=>$picture
        ]);
    }

    /**
     * @Route("/refuseddrdv",name="refusedrdv")
     */
    public function refusedrdv():Response
    {
        $picture = $this->getParameter("app.path.product_images").'/' ;
        return $this->render('rdv/index.html.twig', [
            'user'=>$this->getUser(),
            'r_d_vs' => array_reverse($this->getDoctrine()->getManager()->getRepository(RDV::class)->findBy(['Status'=>'Refused'])),
            'picture'=>$picture
        ]);
    }

    #[Route('/new', name: 'r_d_v_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        if($this->getUser()!=null) {
            $rDV = new RDV();
            $form = $this->createForm(RDVType::class, $rDV);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $rDV->setStatus("Pending");
                $rDV->setUser($this->getUser());
                $entityManager->persist($rDV);
                $entityManager->flush();

                return $this->redirectToRoute('r_d_v_new', ['user' => $this->getUser()], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('rdv/new.html.twig', [
                'user' => $this->getUser(),
                'r_d_v' => $rDV,
                'form' => $form,
            ]);
        }
        return $this->redirectToRoute("app_login");
    }

/*    #[Route('/{id}', name: 'r_d_v_show', methods: ['GET'])]
    public function show(RDV $rDV): Response
    {
        return $this->render('rdv/show.html.twig', [
            'user'=>$this->getUser(),
            'r_d_v' => $rDV,
        ]);
    }*/

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

    #[Route('/accept/{id}', name: 'accept', methods: ['GET'])]
    public function accept(RDV $rDV): Response
    {
        $picture = $this->getParameter("app.path.product_images").'/' ;

        $rDVRepository = $this->getDoctrine()->getRepository(RDV::class) ;

        $rDV ->setStatus('Accepted');
        $notification = new Notification();
        $notification->setOpened(false);
        $notification->setUser($rDV->getUser());
        $notification->setText('Hi '.$rDV->getUser()->getFirstName().' ,Your request to pass '.$rDV->getCertification()->getTitle().' certificate has been accepted
        we will contact soon to give you your vochar stay tuned !');
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($notification);



        $this->getDoctrine()->getManager()->flush();


        return $this->redirectToRoute('r_d_v_index', [
            'user'=>$this->getUser(),
            'r_d_vs' => $rDVRepository->findAll(),
            'picture'=>$picture
        ]);
    }

    #[Route('/refuse/{id}', name: 'refuse', methods: ['GET'])]
    public function refuse(RDV $rDV): Response
    {
        $picture = $this->getParameter("app.path.product_images").'/' ;


        $rDVRepository = $this->getDoctrine()->getRepository(RDV::class) ;
        $rDV ->setStatus('Refused');

        $notification = new Notification();
        $notification->setOpened(false);
        $notification->setUser($rDV->getUser());
        $notification->setText('Hi '.$rDV->getUser()->getFirstName().' ,Your request to pass '.$rDV->getCertification()->getTitle().' certificate has been refused 
        , try to practice more and then try again see you later.');
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($notification);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('r_d_v_index', [
            'user'=>$this->getUser(),
            'r_d_vs' => $rDVRepository->findAll(),
            'picture'=>$picture
        ]);
    }

}
