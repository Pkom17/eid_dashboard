<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of District
 *
 * @author PKom
 * @ORM\Entity(repositoryClass="App\Repository\DistrictRepository")
 * @ORM\Table(name="district")
 */
class District {

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
     * @ORM\column(name="diis_code", type="string",length=10,nullable=true)
     */
    protected $diisCode;

    /**
     * @ORM\column(name="datim_code", type="string",length=30,nullable=true)
     */
    protected $datimCode;

    /**  @ORM\Column(type="boolean",nullable=true) */
    protected $active;

    /**
     * @ORM\ManyToOne(targetEntity="Region", inversedBy="districts")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id")
     */
    protected $region;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Site", mappedBy="district")
     */
    protected $sites;


    public function __construct() {
        $this->sites = new ArrayCollection();
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

    public function getRegion(): ?Region {
        return $this->region;
    }

    public function setRegion(?Region $region): self {
        $this->region = $region;

        return $this;
    }

    /**
     * @return Collection|Site[]
     */
    public function getSites(): Collection {
        return $this->sites;
    }

    public function addSite(Site $site): self {
        if (!$this->sites->contains($site)) {
            $this->sites[] = $site;
            $site->setDistrict($this);
        }

        return $this;
    }

    public function removeSite(Site $site): self {
        if ($this->sites->contains($site)) {
            $this->sites->removeElement($site);
            // set the owning side to null (unless already changed)
            if ($site->getDistrict() === $this) {
                $site->setDistrict(null);
            }
        }

        return $this;
    }

}
