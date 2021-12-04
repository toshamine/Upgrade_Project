<?php

namespace App\Entity;

use App\Repository\CertificationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CertificationRepository::class)
 */
class Certification
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Title;

    /**
     * @ORM\Column(type="string", length=255 , nullable=true)
     */
    private $Picture;

    /**
     * @ORM\OneToMany(targetEntity=Document::class, mappedBy="certification",cascade={"All"})
     */
    private $documents;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="certifications")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=WhiteTest::class, mappedBy="Certification",cascade={"All"})
     */
    private $whitetests;

    /**
     * @ORM\ManyToOne(targetEntity=Company::class, inversedBy="certifications")
     */
    private $company;

    /**
     * @ORM\ManyToOne(targetEntity=Difficulty::class, inversedBy="certifications")
     */
    private $difficulty;

    /**
     * @ORM\OneToMany(targetEntity=RDV::class, mappedBy="Certification",cascade={"All"})
     */
    private $rDVs;


    public function __construct()
    {
        $this->documents = new ArrayCollection();
        $this->whitetests = new ArrayCollection();
        $this->rDVs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPicture(): ?string
    {
        return $this->Picture;
    }

    public function setPicture(string $Picture): self
    {
        $this->Picture = $Picture;

        return $this;
    }

    /**
     * @return Collection|Document[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setCertification($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getCertification() === $this) {
                $document->setCertification(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|WhiteTest[]
     */
    public function getWhitetests(): Collection
    {
        return $this->whitetests;
    }

    public function addWhitetest(WhiteTest $whitetest): self
    {
        if (!$this->whitetests->contains($whitetest)) {
            $this->whitetests[] = $whitetest;
            $whitetest->setCertification($this);
        }

        return $this;
    }

    public function removeWhitetest(WhiteTest $whitetest): self
    {
        if ($this->whitetests->removeElement($whitetest)) {
            // set the owning side to null (unless already changed)
            if ($whitetest->getCertification() === $this) {
                $whitetest->setCertification(null);
            }
        }

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getDifficulty(): ?Difficulty
    {
        return $this->difficulty;
    }

    public function setDifficulty(?Difficulty $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function __toString() {
        return $this->getTitle()." ".$this->getTitle();
    }

    /**
     * @return Collection|RDV[]
     */
    public function getRDVs(): Collection
    {
        return $this->rDVs;
    }

    public function addRDV(RDV $rDV): self
    {
        if (!$this->rDVs->contains($rDV)) {
            $this->rDVs[] = $rDV;
            $rDV->setCertification($this);
        }

        return $this;
    }

    public function removeRDV(RDV $rDV): self
    {
        if ($this->rDVs->removeElement($rDV)) {
            // set the owning side to null (unless already changed)
            if ($rDV->getCertification() === $this) {
                $rDV->setCertification(null);
            }
        }

        return $this;
    }
}
