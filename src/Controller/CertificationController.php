<?php

namespace App\Controller;

use App\Entity\Certification;
use App\Form\CertificationForm;
use App\Form\CertificationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class CertificationController extends AbstractController
{

    /**
     * @Route("/certification", name="certification")
     */
    public function listCertification()
    {

        $em=$this->getDoctrine()->getManager();
        $certification=$em->getRepository("App\Entity\Certification")->findAll();
        $categories=$em->getRepository("App\Entity\Category")->findAll();
        $company=$em->getRepository("App\Entity\Company")->findAll();
        $difficulty=$em->getRepository("App\Entity\Difficulty")->findAll();


        return $this->render("Certification/listCertification.html.twig",["listeCertification"=>$certification,"listCategory"=>$categories,"listDifficulty"=>$difficulty,'listCompany'=>$company]);

    }

    /**
     * @Route("/addCertification",name="add_certification")
     */
    public function addCertification(Request  $request)
    {
        $certification=new Certification();
        $form=$this->createForm(CertificationForm::class,$certification);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid())
        {
            //on récupère les images transmises
            $picture =$form['Picture']->getData();

            //on genere un nouveau nom de fichier

            $fichier=md5(uniqid()).'.'.$picture->guessExtension();

            // on copie le fichier dans le dossier uploads

            $picture->move(
                $this->getParameter('images_directory'),
                $fichier

            );
            //on staocke le nom de l'image dans la base de données


            $certification->setPicture($fichier);

            // pour afficher contenu die
            //var_dump("contenu"); die;
            $em=$this->getDoctrine()->getManager();
            $em->persist($certification);
            $em->flush();
            return $this->redirectToRoute('certification');
        }

        return $this->render('Certification/addCertification.html.twig',['formCertification'=>$form->createView()]);
    }

    /**
     * @Route ("/deleteCertification/{id}",name="CertificationDelete")
     */
    public function deleteCertification($id):Response
    {
        $em=$this->getDoctrine()->getManager();
        $certification=$em->getRepository("App\Entity\Certification")->find($id);

        if($certification!==null){
            $em->remove($certification);
            $em->flush();
        }
        else{
            throw new NotFoundHttpException("the certification with ID ".$id."does not exist");
        }
        return $this->redirectToRoute('certification');
    }

    /**
     * @Route("/UpdateCertification/{id}",name="CertificationUpdate")
     */
    public function UpdateCertififcation(Request $request,Certification  $certification):Response
    {
        $form=$this->createForm(CertificationForm::class,$certification);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){

            //on récupère les images transmises
            $picture =$form['Picture']->getData();



            //on genere un nouveau nom de fichier

            $fichier=md5(uniqid()).'.'.$picture->guessExtension();

            // on copie le fichier dans le dossier uploads

            $picture->move(
                $this->getParameter('images_directory'),
                $fichier

            );
            //on staocke le nom de l'image dans la base de données


            $certification->setPicture($fichier);

            // pour afficher contenu die
            $em=$this->getDoctrine()->getManager();
            $em->persist($certification);
            $em->flush();
            return $this->redirectToRoute('certification');
        }
        return $this->render('Certification/updateCertification.html.twig',[
            'editformCertification'=>$form->createView(),
            'certification'=>$certification,
        ]);



    }
}
