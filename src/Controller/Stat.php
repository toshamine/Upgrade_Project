<?php

namespace App\Controller;

use App\Entity\Company;
use phpDocumentor\Reflection\Types\Integer;

class Stat
{
    public  $nbCompany ;
    public string $nameCompany ;
    public Company $company ;

    /**
     * @return int
     */
    public function getNbCompany(): int
    {
        return $this->nbCompany;
    }

    /**
     * @param int $nbCompany
     */
    public function setNbCompany(int $nbCompany): void
    {
        $this->nbCompany = $nbCompany;
    }

    /**
     * @return string
     */
    public function getNameCompany(): string
    {
        return $this->nameCompany;
    }

    /**
     * @param string $nameCompany
     */
    public function setNameCompany(string $nameCompany): void
    {
        $this->nameCompany = $nameCompany;
    }

    /**
     * @return Company
     */
    public function getCompany(): Company
    {
        return $this->company;
    }

    /**
     * @param Company $company
     */
    public function setCompany(Company $company): void
    {
        $this->company = $company;
    }



}