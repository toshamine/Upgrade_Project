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
    #[Route('/', name: 'white_test_index', methods: ['GET'])]
    public function index(WhiteTestRepository $whiteTestRepository): Response
    {
        return $this->render('white_test/index.html.twig', [
            'white_tests' => $whiteTestRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'white_test_new', methods: ['GET','POST'])]
    public function new(Request $request,$id): Response
    {
        $whiteTest = new WhiteTest();
        $form = $this->createForm(WhiteTestType::class, $whiteTest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $category=$this->getDoctrine()->getRepository(Category::class)->find($id);
            $certification=$this->getDoctrine()->getManager()->getRepository(Certification::class)->findOneBy(['Title'=>(string)$whiteTest->getCertification()]);
            $whiteTest->setCertification($certification);
            $entityManager->persist($whiteTest);
            $entityManager->flush();

            return $this->redirectToRoute('white_test_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Client/AddWhiteTest.html.twig', [
            'white_test' => $whiteTest,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'white_test_show', methods: ['GET'])]
    public function show(WhiteTest $whiteTest): Response
    {
        return $this->render('white_test/show.html.twig', [
            'white_test' => $whiteTest,
        ]);
    }

    #[Route('/{id}/edit', name: 'white_test_edit', methods: ['GET','POST'])]
    public function edit(Request $request, WhiteTest $whiteTest): Response
    {
        $form = $this->createForm(WhiteTestType::class, $whiteTest);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('white_test_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('white_test/edit.html.twig', [
            'white_test' => $whiteTest,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'white_test_delete', methods: ['POST'])]
    public function delete(Request $request, WhiteTest $whiteTest): Response
    {
        if ($this->isCsrfTokenValid('delete'.$whiteTest->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($whiteTest);
            $entityManager->flush();
        }

        return $this->redirectToRoute('white_test_index', [], Response::HTTP_SEE_OTHER);
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
        $em->persist($record);
        $em->flush();
        return $this->render("Client/result.html.twig", ['score' => $score]);
    }


}
