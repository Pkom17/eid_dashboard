<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Site;

/**
 * Description of Plateforme
 *
 * @author PKom
 * @ORM\Entity(repositoryClass="App\Repository\PlateformeRepository")
 * @ORM\Table(name="plateforme")
 */
class Plateforme {

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
     * @ORM\column(name="lab_desc", type="string",length=200,nullable=true)
     */
    protected $labDesc;

    /**
     * @ORM\column(name="lab_location", type="string",length=200,nullable=true)
     */
    protected $labLocation;

    /**
     * @ORM\ManyToOne(targetEntity="Site")
     * @ORM\JoinColumn(name="site_id", referencedColumnName="id",nullable=true)
     */
    protected $site;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LabPrefix", mappedBy="plateforme")
     */
    private $labPrefixes;

    /**
     * @ORM\column(name="eid_active", type="boolean", options={"default":1})
     */
    protected $eidActive = true;

    public function __construct() {
        $this->labPrefixes = new ArrayCollection();
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

    public function isEidActive(): ?bool {
        return $this->eidActive;
    }

    public function setEidActive(bool $eidActive): self {
        $this->eidActive = $eidActive;

        return $this;
    }

    public function getLabDesc(): ?string {
        return $this->labDesc;
    }

    public function setLabDesc(?string $labDesc): self {
        $this->labDesc = $labDesc;

        return $this;
    }

    public function getLabLocation(): ?string {
        return $this->labLocation;
    }

    public function setLabLocation(?string $labLocation): self {
        $this->labLocation = $labLocation;

        return $this;
    }

    public function getSite(): ?Site {
        return $this->site;
    }

    public function setSite(?Site $site): self {
        $this->site = $site;

        return $this;
    }

    /**
     * @return Collection|LabPrefix[]
     */
    public function getLabPrefixes(): Collection {
        return $this->labPrefixes;
    }

    public function addLabPrefix(LabPrefix $labPrefix): self {
        if (!$this->labPrefixes->contains($labPrefix)) {
            $this->labPrefixes[] = $labPrefix;
            $labPrefix->setPlateforme($this);
        }

        return $this;
    }

    public function removeLabPrefix(LabPrefix $labPrefix): self {
        if ($this->labPrefixes->contains($labPrefix)) {
            $this->labPrefixes->removeElement($labPrefix);
            // set the owning side to null (unless already changed)
            if ($labPrefix->getPlateforme() === $this) {
                $labPrefix->setPlateforme(null);
            }
        }

        return $this;
    }

}
