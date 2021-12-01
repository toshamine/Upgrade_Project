<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
    private $Name;

    /**
     * @ORM\OneToMany(targetEntity=Certification::class, mappedBy="category",cascade={"All"})
     */
    private $certifications;

    /**
     * @ORM\OneToMany(targetEntity=User1::class, mappedBy="Category")
     */
    private $users;
    

    public function __construct()
    {
        $this->certifications = new ArrayCollection();
        $this->Users = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    /**
     * @return Collection|Certification[]
     */
    public function getCertifications(): Collection
    {
        return $this->certifications;
    }

    public function addCertification(Certification $certification): self
    {
        if (!$this->certifications->contains($certification)) {
            $this->certifications[] = $certification;
            $certification->setCategory($this);
        }

        return $this;
    }

    public function removeCertification(Certification $certification): self
    {
        if ($this->certifications->removeElement($certification)) {
            // set the owning side to null (unless already changed)
            if ($certification->getCategory() === $this) {
                $certification->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getId()." ".$this->getName() ;
    }

    /**
     * @return Collection|User1[]
     */
    public function getUsers(): Collection
    {
        return $this->Users;
    }

    public function addUser(User1 $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setCategory($this);
        }

        return $this;
    }

    public function removeUser(User1 $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCategory() === $this) {
                $user->setCategory(null);
            }
        }

        return $this;
    }


}
