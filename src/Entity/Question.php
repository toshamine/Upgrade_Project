<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 */
class Question
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Answer;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Question;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ChoiceA;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ChoiceB;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $ChoiceC;

    /**
     * @ORM\ManyToOne(targetEntity=WhiteTest::class, inversedBy="questions",cascade={"persist"})
     */
    private $whiteTest;

    /**
     * @ORM\Column(type="integer")
     */
    private $Duration;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChoices(): ?array
    {
        return $this->Choices;
    }

    public function setChoices(array $Choices): self
    {
        $this->Choices = $Choices;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->Answer;
    }

    public function setAnswer(string $Answer): self
    {
        $this->Answer = $Answer;

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->Question;
    }

    public function setQuestion(string $Question): self
    {
        $this->Question = $Question;

        return $this;
    }

    public function getWhiteTest(): ?WhiteTest
    {
        return $this->whiteTest;
    }

    public function setWhiteTest(?WhiteTest $whiteTest): self
    {
        $this->whiteTest = $whiteTest;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getChoiceA()
    {
        return $this->ChoiceA;
    }

    /**
     * @param mixed $ChoiceA
     */
    public function setChoiceA($ChoiceA): void
    {
        $this->ChoiceA = $ChoiceA;
    }

    /**
     * @return mixed
     */
    public function getChoiceB()
    {
        return $this->ChoiceB;
    }

    /**
     * @param mixed $ChoiceB
     */
    public function setChoiceB($ChoiceB): void
    {
        $this->ChoiceB = $ChoiceB;
    }

    /**
     * @return mixed
     */
    public function getChoiceC()
    {
        return $this->ChoiceC;
    }

    /**
     * @param mixed $ChoiceC
     */
    public function setChoiceC($ChoiceC): void
    {
        $this->ChoiceC = $ChoiceC;
    }

    public function getDuration(): ?int
    {
        return $this->Duration;
    }

    public function setDuration(int $Duration): self
    {
        $this->Duration = $Duration;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getId().' '.$this->getQuestion();
    }


}
