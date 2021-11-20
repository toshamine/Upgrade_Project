<?php

namespace App\Entity;

use App\Repository\RecordsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RecordsRepository::class)
 */
class Records
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
    private $User;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Score;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $WhiteTest;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Certification;

    /**
     * @ORM\Column(type="date", length=255)
     */
    private $Date;

    /**
     * @ORM\Column(type="integer")
     */
    private $Total;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?string
    {
        return $this->User;
    }

    public function setUser(string $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getScore(): ?string
    {
        return $this->Score;
    }

    public function setScore(string $Score): self
    {
        $this->Score = $Score;

        return $this;
    }

    public function getWhiteTest(): ?string
    {
        return $this->WhiteTest;
    }

    public function setWhiteTest(string $WhiteTest): self
    {
        $this->WhiteTest = $WhiteTest;

        return $this;
    }

    public function getCertification(): ?string
    {
        return $this->Certification;
    }

    public function setCertification(string $Certification): self
    {
        $this->Certification = $Certification;

        return $this;
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

    public function getTotal(): ?int
    {
        return $this->Total;
    }

    public function setTotal(int $Total): self
    {
        $this->Total = $Total;

        return $this;
    }
    public function __toString() {
        return (string)$this->getId();
    }
}
