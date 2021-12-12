<?php

namespace App\Controller;

use App\Entity\Company;
use phpDocumentor\Reflection\Types\Integer;

class Stat
{
    public  $nbCompany ;
    public  $nameCompany ;
    public  $company ;

    /**
     * @return mixed
     */
    public function getNbCompany()
    {
        return $this->nbCompany;
    }

    /**
     * @param mixed $nbCompany
     */
    public function setNbCompany($nbCompany): void
    {
        $this->nbCompany = $nbCompany;
    }

    /**
     * @return mixed
     */
    public function getNameCompany()
    {
        return $this->nameCompany;
    }

    /**
     * @param mixed $nameCompany
     */
    public function setNameCompany($nameCompany): void
    {
        $this->nameCompany = $nameCompany;
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     */
    public function setCompany($company): void
    {
        $this->company = $company;
    }




}