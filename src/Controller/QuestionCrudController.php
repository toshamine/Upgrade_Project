<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\WhiteTest;
use App\Form\Question1Type;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/questions')]
class QuestionCrudController extends AbstractController
{
    #[Route('/', name: 'question_crud_index', methods: ['GET'])]
    public function index(QuestionRepository $questionRepository): Response
    {
        return $this->render('Client/questionslist.html.twig', [
            'questions' => $questionRepository->findByOrder()
            ,'user'=>$this->getuser()
        ]);
    }

    #[Route('/new', name: 'question_crud_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        $question = new Question();
        $form = $this->createForm(Question1Type::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $whitetest=$this->getDoctrine()->getManager()->getRepository(WhiteTest::class)->findOneBy(['Title'=>(string)$question->getWhiteTest()]);
            $question->setWhiteTest($whitetest);
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('question_crud_index', ['user'=>$this->getuser()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Client/addquestion.html.twig', [
            'question' => $question,
            'form' => $form
            ,'user'=>$this->getuser()
        ]);
    }

    #[Route('/{id}', name: 'question_crud_show', methods: ['GET'])]
    public function show(Question $question): Response
    {
        return $this->render('question_crud/show.html.twig', [
            'question' => $question
            ,'user'=>$this->getuser()
        ]);
    }

    #[Route('/{id}/edit', name: 'question_crud_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Question $question): Response
    {
        $form = $this->createForm(Question1Type::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('question_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('question_crud/edit.html.twig', [
            'question' => $question,
            'form' => $form
            ,'user'=>$this->getuser()
        ]);
    }

    /**
     * @Route ("deletequestion/{id}",name="deleteqs")
     */
   public function deletequestion(int $id):Response
   {
       $em=$this->getDoctrine()->getManager();
       $question=$em->getRepository(Question::class)->find($id);
       $em->remove($question);
       $em->flush();
       return $this->redirectToRoute('question_crud_index',['user'=>$this->getuser()]);
   }
}
