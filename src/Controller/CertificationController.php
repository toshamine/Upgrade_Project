<?php

namespace App\Controller;

use App\Entity\Certification;
use App\Form\CertificationForm;
use App\Form\CertificationType;
use App\Entity\Document;
use App\Repository\DocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\FileUploader;


class CertificationController extends AbstractController
{
    /**
     * @Route("/certification", name="certification")
     */
    public function listCertification(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(SearchType::class, null, [
            'method' => 'GET'
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $value = $form->getData();
            $certification = $em->getRepository("App\Entity\Certification")->findBy(['Title' => $value]);
            $categories = $em->getRepository("App\Entity\Category")->findAll();
            $company = $em->getRepository("App\Entity\Company")->findAll();
            $difficulty = $em->getRepository("App\Entity\Difficulty")->findAll();
        } else {
            $certification = $em->getRepository("App\Entity\Certification")->findByOrder();;
            $categories = $em->getRepository("App\Entity\Category")->findAll();
            $company = $em->getRepository("App\Entity\Company")->findAll();
            $difficulty = $em->getRepository("App\Entity\Difficulty")->findAll();
        }
        return $this->render("Certification/listCertification.html.twig",['form' => $form->createView(),
            "listeCertification"=>$certification,"listCategory"=>$categories,"listDifficulty"=>$difficulty,
            'listCompany'=>$company]);
    }

    /**
     * @Route("/searchCertification/{value}", name="searchCertification")
     */
    public function searchCertification( Request $request ,string $value)
    {
        var_dump("sz");die;
        // $form->handleRequest($request);

        //   if ($form->isSubmitted() && $form->isValid()) {
        // }
        $em=$this->getDoctrine()->getManager();
        $certification=$em->getRepository("App\Entity\Certification")->findAll();
        $categories=$em->getRepository("App\Entity\Category")->findAll();
        $company=$em->getRepository("App\Entity\Company")->findAll();
        $difficulty=$em->getRepository("App\Entity\Difficulty")->findAll();


        //  return $this->render("Certification/listCertification.html.twig",["listeCertification"=>[],"listCategory"=>[],"listDifficulty"=>[],'listCompany'=>[]]);

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

            //   $fichier=md5(uniqid()).'.'.$picture->guessExtension();
            $filename = "(".$certification->getTitle().")"." ".$picture->getClientOriginalName();


            // on copie le fichier dans le dossier uploads

            $picture->move(
                $this->getParameter('images_directory'),
                // $fichier
                $filename

            );


            //on staocke le nom de l'image dans la base de données
            $certification->setPicture($filename);




//on recupere  les documents transmise

            $documents=$form['documents']->getData();
//on boucle sur les images
            foreach ($documents as $document ){
                //on genere un nouveau nom de fichier

                $filename = "(".$certification->getTitle().")"." ".$document->getClientOriginalName();

                //  $fichierdoc=md5(uniqid()).'.'.$document->guessExtension();
                // on copie le fichier dans le dossier uploads
                $document->move(
                    $this->getParameter('documents_directory'),
                    // $fichierdoc
                    $filename
                );
                // on stocke  les doc dans la BD
                $doc=new Document();
                $doc->setTitle( $filename);
                $certification->addDocument($doc);
            }

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

            if($form['Picture']->getData()!=null) {
                //on récupère les images transmises
                $picture = $form['Picture']->getData();
                //   $image = $form['image']->getData();

//dump($image=$request->query->get('image')->getData());die;


                //on genere un nouveau nom de fichier

                $fichier = md5(uniqid()) . '.' . $picture->guessExtension();
                $filename = "(".$certification->getTitle().")"." ".$picture->getClientOriginalName();

                // on copie le fichier dans le dossier uploads

                $picture->move(
                    $this->getParameter('images_directory'),
                    $filename
                );
                //on staocke le nom de l'image dans la base de données


                $certification->setPicture($filename);

                // pour afficher contenu die
                //var_dump("contenu"); die;

                //on recupere  les documents transmise
            }
            $documents=$form['documents']->getData();
//on boucle sur les images
            foreach ($documents as $document ){
                //on genere un nouveau nom de fichier

                $filename = "(".$certification->getTitle().")"." ".$document->getClientOriginalName();

                $fichierdoc=md5(uniqid()).'.'.$document->guessExtension();
                // on copie le fichier dans le dossier uploads
                $document->move(
                    $this->getParameter('documents_directory'),
                    $filename
                );
                // on stocke  les doc dans la BD
                $doc=new Document();

                $doc->setTitle($filename);
                $certification->addDocument($doc);
            }


            $em=$this->getDoctrine()->getManager();
            $em->persist($certification);
            $em->flush();
            return $this->redirectToRoute('showcertif',['id'=>$certification->getId()]);
        }
        return $this->render('Certification/updateCertification.html.twig',[
            'editformCertification'=>$form->createView(),
            'certification'=>$certification,
        ]);
    }


    /**
     * @Route ("showcertif/{id}",name="showcertif")
     */
    public function showcertif(int $id):Response
    {
        $certif=$this->getDoctrine()->getManager()->getRepository(Certification::class)->find($id);

        return $this->render("Certification/show.html.twig",["certif"=>$certif]);
    }

    /**
     * @Route("/delete/document/{id}/{certif}", name="Certification_delete_document")
     */
    public function deleteDocument(Document $document, string $certif ,Request $request ){

        // $data = json_decode($request->getContent(), true);

        //on verifiE si le token est valide
        //  if($this->isCsrfTokenValid('delete'.$document->getId(), $data['_token'])){



        //on recupere le nom de doc
        $nom=$document->getTitle();


        //on supprime dle fichier
        unlink($this->getParameter('documents_directory').'/'.$nom);

        //on supprime de la base
        $em=$this->getDoctrine()->getManager();
        $id=$em->getRepository(Certification::class)->findOneBy(['Title'=>$certif])->getId();
        $em->remove($document);
        $em->flush();

        //on repond en json
        // return new JsonResponse(['success' => 1]);
        // }else{
        //   return new JsonResponse(['error' => 'Token Invalide'], 400);

        // }

        return $this->redirectToRoute('details_document',['id'=>$id]);

    }
    /**
     * @Route("/document/{id}", name="Certification_download_document")
     */
    //   public function index(DocumentRepository $documentRepository): Response
    public function index(Document $document, Request $request):Response


    {
        $x=$this->getParameter('documents_directory');

        $nom=$document->getTitle();
        $name='/'.$nom;
        return $this->file($x.$name);

        /*    return $this->render('document/index.html.twig', [
                'documents' => $documentRepository->findAll(),
            ]);*/
    }

    /**
     * @Route("/certificationDetails/{id}", name="details_document")
     */
    //   public function index(DocumentRepository $documentRepository): Response
    public function download($id,DocumentRepository $repo):Response


    {
        $em = $this->getDoctrine()->getManager();
        $certification = $em->getRepository("App\Entity\Certification")->find($id);
        $documents = $repo->findByOrder($id);

        return $this->render('Certification/documentlist.html.twig',[
            'documents'=>$documents
        ]);


    }
}
