<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Site
 *
 * @author PKom
 * @ORM\Entity(repositoryClass="App\Repository\SiteRepository")
 * @ORM\Table(name="site")
 * 
 */
class Site {

    public function __construct() {
        $this->active = true;
        $this->eidTests = new ArrayCollection();
        $this->siteContacts = new ArrayCollection();
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\column(name="diis_code", type="string",length=10,nullable = true)
     */
    protected $diisCode;

    /**
     * @ORM\column(type="string",length=100)
     */
    protected $name;

    /**
     * @ORM\column(name="datim_code", type="string",length=30)
     */
    protected $datimCode;

    /**
     * @ORM\column(name="datim_name", type="string",length=200)
     */
    protected $datimName;

    /**  @ORM\Column(type="boolean") */
    protected $priority;

    /**  @ORM\Column(name="vl_test", type="boolean",nullable = true) */
    protected $vlTest;

    /**  @ORM\Column(name="eid_test", type="boolean",nullable = true) */
    protected $eidTest;

    /**  @ORM\Column(name="hiv_followup", type="boolean",nullable = true) */
    protected $hivFollowup;

    /**  @ORM\Column(type="decimal",precision=12,scale=8,nullable = true) */
    protected $latitude;

    /**  @ORM\Column(type="decimal",precision=12,scale=8,nullable = true) */
    protected $longitude;

    /**  @ORM\Column(type="boolean",nullable=true) */
    protected $active;

    /**
     * @ORM\ManyToOne(targetEntity="District",inversedBy="sites")
     * @ORM\JoinColumn(name="district_id", referencedColumnName="id")
     */
    protected $district;

    /**
     * @ORM\ManyToOne(targetEntity="Partner",inversedBy="sites")
     * @ORM\JoinColumn(name="partner_id", referencedColumnName="id")
     */
    protected $partner;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EIDTest", mappedBy="site")
     */
    protected $eidTests;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SiteContact", mappedBy="site")
     */
    protected $siteContacts;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiisCode(): ?string
    {
        return $this->diisCode;
    }

    public function setDiisCode(?string $diisCode): self
    {
        $this->diisCode = $diisCode;

        return $this;
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

    public function getDatimCode(): ?string
    {
        return $this->datimCode;
    }

    public function setDatimCode(string $datimCode): self
    {
        $this->datimCode = $datimCode;

        return $this;
    }

    public function getDatimName(): ?string
    {
        return $this->datimName;
    }

    public function setDatimName(string $datimName): self
    {
        $this->datimName = $datimName;

        return $this;
    }

    public function getPriority(): ?bool
    {
        return $this->priority;
    }

    public function setPriority(bool $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getVlTest(): ?bool
    {
        return $this->vlTest;
    }

    public function setVlTest(?bool $vlTest): self
    {
        $this->vlTest = $vlTest;

        return $this;
    }

    public function getEidTest(): ?bool
    {
        return $this->eidTest;
    }

    public function setEidTest(?bool $eidTest): self
    {
        $this->eidTest = $eidTest;

        return $this;
    }

    public function getHivFollowup(): ?bool
    {
        return $this->hivFollowup;
    }

    public function setHivFollowup(?bool $hivFollowup): self
    {
        $this->hivFollowup = $hivFollowup;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getDistrict(): ?District
    {
        return $this->district;
    }

    public function setDistrict(?District $district): self
    {
        $this->district = $district;

        return $this;
    }

    public function getPartner(): ?Partner
    {
        return $this->partner;
    }

    public function setPartner(?Partner $partner): self
    {
        $this->partner = $partner;

        return $this;
    }

    /**
     * @return Collection|EIDTest[]
     */
    public function getEidTests(): Collection
    {
        return $this->eidTests;
    }

    public function addEidTest(EIDTest $eidTest): self
    {
        if (!$this->eidTests->contains($eidTest)) {
            $this->eidTests[] = $eidTest;
            $eidTest->setSite($this);
        }

        return $this;
    }

    public function removeEidTest(EIDTest $eidTest): self
    {
        if ($this->eidTests->contains($eidTest)) {
            $this->eidTests->removeElement($eidTest);
            // set the owning side to null (unless already changed)
            if ($eidTest->getSite() === $this) {
                $eidTest->setSite(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SiteContact[]
     */
    public function getSiteContacts(): Collection
    {
        return $this->siteContacts;
    }

    public function addSiteContact(SiteContact $siteContact): self
    {
        if (!$this->siteContacts->contains($siteContact)) {
            $this->siteContacts[] = $siteContact;
            $siteContact->setSite($this);
        }

        return $this;
    }

    public function removeSiteContact(SiteContact $siteContact): self
    {
        if ($this->siteContacts->contains($siteContact)) {
            $this->siteContacts->removeElement($siteContact);
            // set the owning side to null (unless already changed)
            if ($siteContact->getSite() === $this) {
                $siteContact->setSite(null);
            }
        }

        return $this;
    }


}
