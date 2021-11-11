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
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="whiteTest",cascade={"All"})
     */
    private $questions;

    /**
     * @ORM\ManyToOne(targetEntity=Certification::class, inversedBy="whiteTests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Certification;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Title;


    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getChoiceA(): ?string
    {
        return $this->ChoiceA;
    }

    public function setChoiceA(string $ChoiceA): self
    {
        $this->ChoiceA = $ChoiceA;

        return $this;
    }

    public function getChoiceB(): ?string
    {
        return $this->ChoiceB;
    }

    public function setChoiceB(string $ChoiceB): self
    {
        $this->ChoiceB = $ChoiceB;

        return $this;
    }

    public function getChoiceC(): ?string
    {
        return $this->ChoiceC;
    }

    public function setChoiceC(string $ChoiceC): self
    {
        $this->ChoiceC = $ChoiceC;

        return $this;
    }

    public function nbquestion():?int
    {
        return $this->questions->count();
    }

    public function __toString() {
        return (string)$this->getTitle();
    }

    public function getCertification(): ?Certification
    {
        return $this->Certification;
    }

    public function setCertification(?Certification $Certification): self
    {
        $this->Certification = $Certification;

        return $this;
    }

    public function certiftitle():?string
    {
        return (string)$this->getCertification();
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }


}
