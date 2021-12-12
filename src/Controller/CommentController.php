<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Notification;
use App\Entity\RDV;
use App\Entity\User1;
use App\Repository\CommentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Certification;
use App\Form\CommentType;
use Symfony\Component\Routing\Annotation\Route;


class CommentController extends AbstractController

{
    /**
     * @Route("/certificationCommentDetails/{id}", name="details_comment")
     */
    public function listComment(Request $request,$id, CommentRepository $repo,PaginatorInterface $paginator): Response

    {

        $em = $this->getDoctrine()->getManager();
        $user1 = $em->getRepository(User1::class)->findOneBy(['email'=>$this->getUser()->getUserIdentifier()]);
        $picture = $this->getParameter("app.path.product_images").'/'.$user1->getPicture();

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $certification=$em->getRepository("App\Entity\Certification")->find($id);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $comment->setUser($user);
            $comment->setCertification($certification);
            $content= $request->request->get('content');
            $comment->getContent($content);
            $comment->setCreatedat(new \DateTime('now'));

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('details_comment', ['id'=>$id,'user'=>$this->getUser()]
            );
        }

        $commentlist = $repo->findByOrder($id);
        $comments=$paginator->paginate(
            $commentlist,
            $request->query->getInt('page',1),5);
        return $this->render('comment/testbootstrap.html.twig', [
            'comments' => $comments,'certifID'=>$id, 'pic'=>$picture,'comment' => $comment,
            'form' => $form->createView(),
            'user'=>$this->getUser(),
            'alertrdv'=>count($this->getDoctrine()->getManager()->getRepository(RDV::class)->findBy(['Status'=>"Pending"]))
        ]);
    }



    /**
     * @Route ("/deletecomment/{id}",name="commentDelete")
     */
    public function deleteComment($id):Response
    {
        $em=$this->getDoctrine()->getManager();
        $comment=$em->getRepository("App\Entity\Comment")->find($id);
        $certification=$comment->getCertification();
        $id_certif=$certification->getId();

        if($comment!==null){
            $em->remove($comment);
            $em->flush();
        }
        else{
            throw new NotFoundHttpException("the comment with ID ".$id."does not exist");
        }
        return $this->redirectToRoute('details_comment',['id'=>$id_certif,'user'=>$this->getUser()]);
    }


    /**
     * @Route("/Comment/new/{id}",name="CreateNewCom")
     */
    public function newComment(Request $request,$id): Response
    {


        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);


        $em=$this->getDoctrine()->getManager();

        $certification=$em->getRepository("App\Entity\Certification")->find($id);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $comment->setUser($user);
            $comment->setCertification($certification);
            $content= $request->request->get('content');
            $comment->getContent($content);
            $comment->setCreatedat(new \DateTime('now'));

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('details_comment', ['id'=>$id,'user'=>$this->getUser()]);
        }

        return $this->renderForm('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form,
            'id'=>$id,
            'user'=>$this->getUser(),
            'alertrdv'=>count($this->getDoctrine()->getManager()->getRepository(RDV::class)->findBy(['Status'=>"Pending"]))
        ]);

    }



    /**
     * @Route("/Response/new/{id}",name="CreateNewResp")
     */
    public function newResponse(Request $request,$id): Response
    {


        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);


        $em=$this->getDoctrine()->getManager();
        $parentComment=$em->getRepository("App\Entity\Comment")->find($id);


        $certification=$parentComment->getCertification();
        $certifID=$certification->getId();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $comment->setUser($user);
            $comment->setCertification($certification);
            $comment->setParent($parentComment);
            $comment->setCreatedat(new \DateTime('now'));
            $content= $request->request->get('content');
            $comment->getContent($content);

            $notif=new Notification();
            $notif->setUser($comment->getParent()->getUser());
            $notif->setOpened(false);
            $notif->setDate(new \DateTime('now'));
            $notif->setText($comment->getUser()->getFirstName().' Replied To Your Comment On '.$certification->getTitle().' Certificate');
            $notif->setCertification($certification->getId());
            $em->persist($notif);
            $em->flush();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('details_comment', ['id'=>$certifID,'user'=>$this->getUser()]);
        }

        return $this->renderForm('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form,
            'id'=>$id,
            'user'=>$this->getUser(),
            'alertrdv'=>count($this->getDoctrine()->getManager()->getRepository(RDV::class)->findBy(['Status'=>"Pending"]))
        ]);

    }

    /**
     * @Route("/Comment/update/{id}",name="UpdateCom")
     */
    public function update(Request $request,$id,Comment $comment): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $commentaire=$form['content']->getData();
            $comment->setContent($commentaire);



            $em=$this->getDoctrine()->getManager();

            $certifid=$comment->getCertification()->getId();
            $em->persist($comment);
            $em->flush();


            return $this->redirectToRoute('details_comment', ['id'=> $certifid,'user'=>$this->getUser()]);
        }
        $certifid=$comment->getCertification()->getId();

        return $this->renderForm('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form,
            'id'=>$certifid,
            'user'=>$this->getUser(),
            'alertrdv'=>count($this->getDoctrine()->getManager()->getRepository(RDV::class)->findBy(['Status'=>"Pending"]))

        ]);

    }







}