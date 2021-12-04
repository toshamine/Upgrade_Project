<?php

namespace App\Entity;

use App\Repository\CheatersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CheatersRepository::class)
 */
class Cheaters
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
    private $Whitestest;

    /**
     * @ORM\Column(type="date", length=255)
     */
    private $Date;

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

    public function getWhitestest(): ?string
    {
        return $this->Whitestest;
    }

    public function setWhitestest(string $Whitestest): self
    {
        $this->Whitestest = $Whitestest;

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
}
