<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Partner
 *
 * @author PKom
 * @ORM\Entity(repositoryClass="App\Repository\PartnerRepository")
 * @ORM\Table(name="partner")
 */
class Partner {

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

    /**  @ORM\Column(type="boolean",nullable=true) */
    protected $active;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Site", mappedBy="partner")
     */
    private $sites;


    public function __construct() {
        $this->sites = new ArrayCollection();
        $this->eidTests = new ArrayCollection();
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

    /**
     * @return Collection|Site[]
     */
    public function getSites(): Collection {
        return $this->sites;
    }

    public function addSite(Site $site): self {
        if (!$this->sites->contains($site)) {
            $this->sites[] = $site;
            $site->setPartner($this);
        }

        return $this;
    }

    public function removeSite(Site $site): self {
        if ($this->sites->contains($site)) {
            $this->sites->removeElement($site);
            // set the owning side to null (unless already changed)
            if ($site->getPartner() === $this) {
                $site->setPartner(null);
            }
        }

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

}
