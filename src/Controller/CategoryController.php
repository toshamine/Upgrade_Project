<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryForm;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;



class CategoryController extends AbstractController
{
/**
 * @Route("/category",name="category")
 */
    public function listCategory()
    {
        $em=$this->getDoctrine()->getManager();
        $categories=$em->getRepository("App\Entity\Category")->findAll();


        return $this->render('category/listCategory.html.twig',["listCategory"=>$categories]);
    }

    /**
     * @Route ("/addCategory",name="add_category")
     */
    public function addCategory(Request $request)
    {
        $category=new Category();
        $form=$this->createForm(CategoryForm::class,$category);

        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid() ){
            $em=$this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('category');
        }

        return $this->render('category/addCategory.html.twig',['formCategory'=>$form->createView()]);
    }

    /**
     * @Route("/deleteCategory/{id}",name="categoryDelete")
     */
    public function deleteCategory($id)
    {
        $em=$this->getDoctrine()->getManager();
        $category=$em->getRepository("App\Entity\Category")->find($id) ;

        if ($category !==null){
            $em->remove($category);
            $em->flush();
        }
        else{
            throw new NotFoundHttpException("Category NOt Found");
        }
        return $this->redirectToRoute('category');
    }

/**
 * @Route("/updateCategory/{id}",name="categoryUpdate")
 */
public function updateCategory(Request $request, $id)
{
    $em=$this->getDoctrine()->getManager();
    $category=$em->getRepository("App\Entity\Category")->find($id);

    $editform=$this->createForm(CategoryForm::class,$category);
    $editform->handleRequest($request);
    if($editform->isSubmitted() and$editform->isValid()){

        $em->persist($category);
        $em->flush();
        return $this->redirectToRoute('category');
    }

    return $this->render('category/updateCategory.html.twig',['editFormCategory'=>$editform->createView()]);

}

}