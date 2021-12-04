<?php

namespace App\Entity;

use App\Repository\RDVRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RDVRepository::class)
 */
class RDV
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne (targetEntity=User1::class, inversedBy="rDVs")
     */
    private $User;

    /**
     * @ORM\Column(type="date")
     */
    private $Date;

    /**
     * @ORM\ManyToOne(targetEntity=Certification::class, inversedBy="rDVs")
     */
    private $Certification;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Status;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getUser(): ?User1
    {
        return $this->User;
    }

    public function setUser(User1 $user): self
    {
        $this->User=$user;
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

    public function getCertification(): ?Certification
    {
        return $this->Certification;
    }

    public function setCertification(?Certification $Certification): self
    {
        $this->Certification = $Certification;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->Status;
    }

    public function setStatus(string $Status): self
    {
        $this->Status = $Status;

        return $this;
    }
}
