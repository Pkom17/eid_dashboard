<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of EIDAgeCategory
 *
 * @author PKom
 * @ORM\Entity(repositoryClass="App\Repository\EIDAgeCategoryRepository")
 * @ORM\Table(name="eid_age_category")
 */
class EIDAgeCategory {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\column(type="string",length=30)
     */
    protected $name;

    /**
     * @ORM\column(type="integer",name="age_min")
     */
    protected $ageMin;

    /**
     * @ORM\column(type="integer",name="age_max")
     */
    protected $ageMax;

    /**
     * @ORM\column(type="integer",name="level")
     */
    protected $level;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAgeMin(): ?int
    {
        return $this->ageMin;
    }

    public function setAgeMin(int $ageMin): self
    {
        $this->ageMin = $ageMin;

        return $this;
    }

    public function getAgeMax(): ?int
    {
        return $this->ageMax;
    }

    public function setAgeMax(int $ageMax): self
    {
        $this->ageMax = $ageMax;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

}
