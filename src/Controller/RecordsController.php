<?php

namespace App\Controller;

use App\Entity\Records;
use App\Form\RecordsType;
use App\Repository\RecordsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/records')]
class RecordsController extends AbstractController
{
    #[Route('/', name: 'records_index', methods: ['GET'])]
    public function index(RecordsRepository $recordsRepository): Response
    {
        return $this->render('records/index.html.twig', [
            'records' => $recordsRepository->findByOrder()
            ,'user'=>$this->getuser()
        ]);
    }
    #[Route('/deleteallrec', name: 'deleteallrec', methods: ['GET','POST'])]
    public function edit(): Response
    {
        $em=$this->getDoctrine()->getManager();
        $record=$em->getRepository(Records::class)->findAll();
        $i=0;
        while($i!=count($record)){
            $em->remove($record[$i]);
            $em->flush();
            $i++;
        }
        return $this->redirectToRoute('records_index',['user'=>$this->getuser()]);
    }

    /**
     * @Route ("/deleterec/{id}",name="deleterec")
     */
   public function deleterecord(int $id):Response
   {
       $em=$this->getDoctrine()->getManager();
       $record=$em->getRepository(Records::class)->find($id);
       $em->remove($record);
       $em->flush();
       return $this->redirectToRoute('records_index',['user'=>$this->getuser()]);
   }
}
