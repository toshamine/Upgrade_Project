<?php

namespace App\Entity;

use App\Repository\WhiteTestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WhiteTestRepository::class)
 */
class WhiteTest
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date",nullable=true)
     */
    private $Date;

    /**
     * @ORM\Column(type="time",nullable=true)
     */
    private $Limit_Time;

    /**
     * @ORM\Column(type="time",nullable=true)
     */
    private $Time;

    /**
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="whiteTest")
     */
    private $questions;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function getLimitTime(): ?\DateTimeInterface
    {
        return $this->Limit_Time;
    }

    public function setLimitTime(\DateTimeInterface $Limit_Time): self
    {
        $this->Limit_Time = $Limit_Time;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->Time;
    }

    public function setTime(\DateTimeInterface $Time): self
    {
        $this->Time = $Time;

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setWhiteTest($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getWhiteTest() === $this) {
                $question->setWhiteTest(null);
            }
        }

        return $this;
    }
}
