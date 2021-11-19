<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Certification;
use App\Entity\Question;
use App\Entity\Records;
use App\Entity\WhiteTest;
use App\Form\WhiteTestType;
use App\Repository\WhiteTestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/whitetest')]
class WhiteTestController extends AbstractController
{
    #[Route('/{certif}', name: 'white_test_index', methods: ['GET'])]
    public function index(WhiteTestRepository $whiteTestRepository,string $certif): Response
    {
        $em=$this->getDoctrine()->getManager();
        $certification=$em->getRepository(Certification::class)->findOneBy(['Title'=>$certif]);
        return $this->render('white_test/index.html.twig', [
            'white_tests' => $whiteTestRepository->findBy(['Certification'=>$certification]),'certif'=>$certif
        ]);
    }

    /**
     * @Route ("/new/test",name="newtest")
     */
    public function new(Request $request): Response
    {
        $whiteTest = new WhiteTest();
        $form = $this->createForm(WhiteTestType::class, $whiteTest);
        $form->handleRequest($request);
        $certif="test";
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $certification=$this->getDoctrine()->getManager()->getRepository(Certification::class)->findOneBy(['Title'=>(string)$whiteTest->getCertification()]);
            $whiteTest->setCertification($certification);
            $entityManager->persist($whiteTest);
            $entityManager->flush();
            $certif=$certification->getTitle();
            return $this->redirectToRoute('white_test_index', ['certif'=>$certification->getTitle()]);
        }

        return $this->renderForm('Client/AddWhiteTest.html.twig', [
            'white_test' => $whiteTest,
            'form' => $form,
            'certif'=>$certif
        ]);
    }

    /**
     * @Route ("/show/{id}",name="showwh")
     */
    public function show(int $id): Response
    {
        $whitetest=$this->getDoctrine()->getManager()->getRepository(WhiteTest::class)->find($id);
        return $this->render('white_test/show.html.twig', [
            'whitetest' => $whitetest,
        ]);
    }

    #[Route('/{id}/{certif}/edit', name: 'white_test_edit', methods: ['GET','POST'])]
    public function edit(Request $request, WhiteTest $whiteTest,string $certif): Response
    {
        $form = $this->createForm(WhiteTestType::class, $whiteTest);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('white_test_index', ['certif'=>$certif], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Client/EditWhiteTest.html.twig', [
            'white_test' => $whiteTest,
            'form' => $form,
            'certif'=>$certif
        ]);
    }

    /**
     * @Route("/deletewh/{id}/{certif}",name="deletewh")
     */
    public function delete(int $id,string $certif):Response
    {
        $whitetest=$this->getDoctrine()->getManager()->getRepository(WhiteTest::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($whitetest);
        $em->flush();
        return $this->redirectToRoute("white_test_index",['certif'=>$certif]);
    }

    /**
     * @Route("passerWT/{id}/{i}/e73f1ece5087b8a5ae33998952202202{answer}e73f1ece5087b8a5ae33998952202202/7d60febfd9c79bf45ec6126f14dfe69a57444099{score}7d60febfd9c79bf45ec6126f14dfe69a57444099",name="passerWT")
     * @throws \Exception
     */
    public function passer($id,$i,$answer,$score):Response
    {
        $em=$this->getDoctrine()->getManager();
        $questions=$em->getRepository(Question::class)->findBy(['whiteTest'=>$id]);
        $whitetest=$em->getRepository(WhiteTest::class)->find($id);
        $length=count($questions);
        $j=$i-1;
        if($i!=0) {
            if ($answer == $questions[$j]->getAnswer()) {
                $score++;
            }
        }
        while($i != $length){
        return $this->render("Client/PasserWT.html.twig",['question'=>$questions[$i],'whitetestid'=>$id,'i'=>$i,'score'=>$score,'whitetest'=>$whitetest]);
        }
        $whitetest=$questions[0]->getWhiteTest();
        $em=$this->getDoctrine()->getManager();
        $record = new Records();
        $record->setUser("Test User");
        $record->setScore((string)$score);
        $record->setWhiteTest((string)$whitetest);
        $record->setCertification((string)$whitetest->getCertification());
        $record->setDate(new \DateTime('now'));
        $record->setTotal($whitetest->nbquestion());
        $em->persist($record);
        $em->flush();
        return $this->render("Client/result.html.twig", ['score' => $score,'nbquestions'=>$whitetest->nbquestion()]);
    }
}
