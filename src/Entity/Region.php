<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Region
 *
 * @author PKom
 * 
 * @ORM\Entity(repositoryClass="App\Repository\RegionRepository")
 * @ORM\Table(name="region")
 */
class Region {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\column(type="string",length=100)
     */
    protected $name;

    /**
     * @ORM\column(name="diis_code",type="string",length=10,nullable = true)
     */
    protected $diisCode;

    /**
     * @ORM\column(name="datim_code", type="string",length=30,nullable = true)
     */
    protected $datimCode;

    /**  @ORM\Column(type="boolean",nullable=true) */
    protected $active;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\District", mappedBy="region")
     */
    private $districts;


    public function __construct() {
        $this->districts = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getDiisCode(): ?string {
        return $this->diisCode;
    }

    public function setDiisCode(?string $diisCode): self {
        $this->diisCode = $diisCode;

        return $this;
    }

    public function getDatimCode(): ?string {
        return $this->datimCode;
    }

    public function setDatimCode(?string $datimCode): self {
        $this->datimCode = $datimCode;

        return $this;
    }

    public function getActive(): ?bool {
        return $this->active;
    }

    public function setActive(?bool $active): self {
        $this->active = $active;

        return $this;
    }

}
