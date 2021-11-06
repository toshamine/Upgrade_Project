<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Certification;
use App\Entity\Question;
use App\Entity\WhiteTest;
use App\Form\WhiteTestType;
use App\Repository\WhiteTestRepository;
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
     * @Route("passerWT/{id}",name="passerWT")
     */
    public function passer($id,Request $request,PaginatorInterface $paginator):Response
    {
        $em=$this->getDoctrine()->getManager();
        $questions=$em->getRepository(Question::class)->findBy(['whiteTest'=>$id]);
        $questions=$paginator->paginate(
            $questions,
            $request->query->getInt('page',1),1
    );

        return $this->render("Client/PasserWT.html.twig",['questions'=>$questions]);
    }

    /**
     * @Route("/score/{question}/{answer}/{end}",name="score")
     */
    public function score($question,$answer,$end){
        $score=0;
        if($end!="end") {
            if ($question == $answer) {
                $score++;
            }
        }
        return $this->render("Client/result.html.twig",['score'=>$score]);
    }
}
