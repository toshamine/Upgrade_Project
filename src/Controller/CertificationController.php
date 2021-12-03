<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Certification;
use App\Form\CertificationForm;
use App\Form\CertificationType;
use App\Entity\Document;
use App\Repository\DocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\FileUploader;


class CertificationController extends AbstractController
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @Route("/certification", name="certification")
     */
    public function listCertification(Request $request,PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(SearchType::class, null, [
            'method' => 'GET'
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $value = $form->getData();
            $certification = $em->getRepository("App\Entity\Certification")->findBy(['Title' => strtoupper($value)]);
            $categories = $em->getRepository("App\Entity\Category")->findAll();
            $company = $em->getRepository("App\Entity\Company")->findAll();
            $difficulty = $em->getRepository("App\Entity\Difficulty")->findAll();
        } else {
            $certification = $em->getRepository("App\Entity\Certification")->findByOrder();
            $categories = $em->getRepository("App\Entity\Category")->findAll();
            $company = $em->getRepository("App\Entity\Company")->findAll();
            $difficulty = $em->getRepository("App\Entity\Difficulty")->findAll();

        }
        $certifications=$paginator->paginate(
            $certification,
            $request->query->getInt('page',1),4
        );
        return $this->render("Certification/listCertification.html.twig",['form' => $form->createView(),
            "listeCertification"=>$certifications,"listCategory"=>$categories,"listDifficulty"=>$difficulty,
            'listCompany'=>$company,'user'=>$this->getuser()]);

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

        return $this->render('Certification/addCertification.html.twig',['form'=>$form->createView(),'user'=>$this->getuser()]);
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
        return $this->redirectToRoute('certification',['user'=>$this->getuser()]);
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
            return $this->redirectToRoute('showcertif',['id'=>$certification->getId(),'user'=>$this->getuser()]);
        }
        return $this->render('Certification/updateCertification.html.twig',[
            'editformCertification'=>$form->createView(),
            'certification'=>$certification,
            'user'=>$this->getuser()
        ]);
    }


    /**
     * @Route ("showcertif/{id}",name="showcertif")
     */
    public function showcertif(int $id):Response
    {
        $certif=$this->getDoctrine()->getManager()->getRepository(Certification::class)->find($id);

        return $this->render("Certification/show.html.twig",["certif"=>$certif,'user'=>$this->getuser()]);
    }

    /**
     * @Route("/delete/document/{id}/{certif}", name="Certification_delete_document")
     */
    public function deleteDocument(Document $document,string $certif ,Request $request ){

        //on recupere le nom de doc
        $nom=$document->getTitle();


        //on supprime dle fichier
        unlink($this->getParameter('documents_directory').'/'.$nom);

        //on supprime de la base
        $em=$this->getDoctrine()->getManager();
        $id=$em->getRepository(Certification::class)->findOneBy(['Title'=>$certif])->getId();
        $em->remove($document);
        $em->flush();



        return $this->redirectToRoute('details_document',['id'=>$id,'user'=>$this->getuser()]);

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
            ,'user'=>$this->getuser()
        ]);


    }

    /**
     * @Route("/certificationfilter/{filter}", name="certificationfilter")
     */
    public function listCertificationfilter(Request $request,$filter,PaginatorInterface $paginator)
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
            $certification = $em->getRepository("App\Entity\Certification")->findByOrder();
            $categories = $em->getRepository("App\Entity\Category")->findAll();
            $company = $em->getRepository("App\Entity\Company")->findAll();
            $difficulty = $em->getRepository("App\Entity\Difficulty")->findAll();

        }
        $finallist= array();
        $cat=array();
        $com=array();
        $diff=array();
        $i=0;
        $test=explode(",",$filter);

            while($i!=count($test)) {
                if($test[$i]<=200) {
                            array_push($cat,$test[$i]);
                        }
                if($test[$i]>200 && $test[$i]<300) {
                    array_push($com,$test[$i]);
                }
                if($test[$i]>=300) {
                    array_push($diff,$test[$i]);
                }
                $i++;
        }


            if(count($cat)!=0){
                foreach ($certification as $c) {
                    if(!in_array($c->getCategory()->getId(),$cat))
                    {
                        $key = array_search($c, $certification);
                        unset($certification[$key]);
                    }
                }
            }
        if(count($com)!=0){
            foreach ($certification as $c) {
                if(!in_array($c->getCompany()->getId(),$com))
                {
                    $key = array_search($c, $certification);
                    unset($certification[$key]);
                }
            }
        }
        if(count($diff)!=0){
            foreach ($certification as $c) {
                if(!in_array($c->getDifficulty()->getId(),$diff))
                {
                    $key = array_search($c, $certification);
                    unset($certification[$key]);
                }
            }
        }

        $certifications=$paginator->paginate(
            $certification,
            $request->query->getInt('page',1),4
        );

        return $this->render("Certification/listCertification.html.twig",['form' => $form->createView(),
            "listeCertification"=>$certifications,"listCategory"=>$categories,"listDifficulty"=>$difficulty,
            'listCompany'=>$company,'user'=>$this->getuser()]);

    }


}
