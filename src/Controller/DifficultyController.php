<?php

namespace App\Controller;

use App\Entity\Difficulty;
use App\Form\DifficultyForm;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
class DifficultyController extends AbstractController
{
    /**
     * @Route("/difficulty",name="difficulty")
     */
    public function listDifficulty()
    {
        $em=$this->getDoctrine()->getManager();
        $difficulties=$em->getRepository("App\Entity\Difficulty")->findAll();


        return $this->render('difficulty/listDifficulty.html.twig',["listDifficulty"=>$difficulties]);
    }

    /**
     * @Route ("/addDifficulty",name="add_difficulty")
     */
    public function addDifficulty(Request $request)
    {
        $difficulty=new Difficulty();
        $form=$this->createForm(DifficultyForm::class,$difficulty);

        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid() ){
            $em=$this->getDoctrine()->getManager();
            $em->persist($difficulty);
            $em->flush();
            return $this->redirectToRoute('difficulty');
        }

        return $this->render('difficulty/addDifficulty.html.twig',['formDifficulty'=>$form->createView()]);
    }

    /**
     * @Route("/deleteDifficulty/{id}",name="difficultyDelete")
     */
    public function deleteDifficulty($id)
    {
        $em=$this->getDoctrine()->getManager();
        $difficulty=$em->getRepository("App\Entity\Difficulty")->find($id) ;

        if ($difficulty !==null){
            $em->remove($difficulty);
            $em->flush();
        }
        else{
            throw new NotFoundHttpException("Difficulty NOt Found");
        }
        return $this->redirectToRoute('difficulty');
    }

    /**
     * @Route("/updateDifficulty/{id}",name="difficultyUpdate")
     */
    public function updateCategory(Request $request, $id)
    {
        $em=$this->getDoctrine()->getManager();
        $difficulty=$em->getRepository("App\Entity\Difficulty")->find($id);

        $editform=$this->createForm(CategoryForm::class,$difficulty);
        $editform->handleRequest($request);
        if($editform->isSubmitted() and$editform->isValid()){

            $em->persist($difficulty);
            $em->flush();
            return $this->redirectToRoute('difficulty');
        }

        return $this->render('category/updateDifficulty.html.twig',['editFormDifficulty'=>$editform->createView()]);

    }





}