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
     * @ORM\Column(type="array")
     */
    private $Choices = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Answer;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Question;

    /**
     * @ORM\ManyToOne(targetEntity=WhiteTest::class, inversedBy="questions")
     */
    private $whiteTest;

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

    public function __toString(): string
    {
        return $this->id ;
    }
}
