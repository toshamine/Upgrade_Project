<?php

namespace App\Entity;

use App\Repository\ManagerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ManagerRepository::class)
 */
class Manager extends User
{

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Expertise;


    public function getExpertise(): ?string
    {
        return $this->Expertise;
    }

    public function setExpertise(string $Expertise): self
    {
        $this->Expertise = $Expertise;

        return $this;
    }
}
