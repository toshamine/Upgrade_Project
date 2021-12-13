<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NotificationRepository::class)
 */
class Notification
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User1::class, inversedBy="notifications")
     */
    private $User;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Text;

    /**
     * @ORM\Column(type="date")
     */
    private $Date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Opened;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $Certification;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cheater;

    public function __construct()
    {
        $this->Date = new \DateTime();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User1
    {
        return $this->User;
    }

    public function setUser(?User1 $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->Text;
    }

    public function setText(string $Text): self
    {
        $this->Text = $Text;

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

    public function getOpened(): ?bool
    {
        return $this->Opened;
    }

    public function setOpened(bool $Opened): self
    {
        $this->Opened = $Opened;

        return $this;
    }

    public function getCertification(): ?int
    {
        return $this->Certification;
    }

    public function setCertification(int $Certification): self
    {
        $this->Certification = $Certification;

        return $this;
    }

    public function getCheater(): ?int
    {
        return $this->cheater;
    }

    public function setCheater(?int $cheater): self
    {
        $this->cheater = $cheater;

        return $this;
    }


}
