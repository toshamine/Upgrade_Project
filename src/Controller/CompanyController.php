<?php

namespace App\Controller;

use App\Form\CompanyForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Company;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;


class CompanyController extends AbstractController
{

    /**
     * @Route("/company",name="company")
     */
    public function listCompany()
    {
        $em=$this->getDoctrine()->getManager();
        $companies=$em->getRepository("App\Entity\Company")->findAll();


        return $this->render('company/listCompany.html.twig',["listCompany"=>$companies]);
    }

    /**
     * @Route ("/addCompany",name="add_company")
     */
    public function addCompany(Request $request)
    {
        $company=new Company();
        $form=$this->createForm(CompanyForm::class,$company);

        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid() ){
            $em=$this->getDoctrine()->getManager();
            $em->persist( $company);
            $em->flush();
            return $this->redirectToRoute('company');
        }

        return $this->render('company/addCompany.html.twig',['formCompany'=>$form->createView()]);
    }



    /**
     * @Route("/deleteCompany/{id}",name="companyDelete")
     */
    public function deleteCompany($id)
    {
        $em=$this->getDoctrine()->getManager();
        $company=$em->getRepository("App\Entity\Company")->find($id) ;

        if ($company !==null){
            $em->remove($company);
            $em->flush();
        }
        else{
            throw new NotFoundHttpException("Company NOt Found");
        }
        return $this->redirectToRoute('company');
    }

    /**
     * @Route("/updateCompany/{id}",name="companyUpdate")
     */
    public function updateCompany(Request $request, $id)
    {
        $em=$this->getDoctrine()->getManager();
        $company=$em->getRepository("App\Entity\Company")->find($id);

        $editform=$this->createForm(CompanyForm::class,$company);
        $editform->handleRequest($request);
        if($editform->isSubmitted() and$editform->isValid()){

            $em->persist($company);
            $em->flush();
            return $this->redirectToRoute('company');
        }

        return $this->render('company/updateCompany.html.twig',['editFormCompany'=>$editform->createView()]);

    }


}