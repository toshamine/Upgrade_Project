<?php

namespace App\Controller;

use App\Entity\User1;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InstructorsController extends AbstractController
{
    /**
     * @Route ("/listinstructors",name="listinstructors")
     */
   public function listinstructors(PaginatorInterface $paginator,Request $request):Response
   {
       $instructors=$this->getDoctrine()->getRepository(User1::class)->findAll();
       $finalist=array();
       foreach ($instructors as $in) {
           if (in_array("ROLE_MANAGER", $in->getRoles())) {
               array_push($finalist,$in );
           }
       }
       $pglist=$paginator->paginate(
           $finalist,
           $request->query->getInt('page',1),4
       );
       return $this->render("Instructors/instructors.html.twig",['instructors'=>$pglist,'user'=>$this->getuser()]);
   }
}
