<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of LabPrefix
 *
 * @author PKom
 * @ORM\Entity(repositoryClass="App\Repository\LabPrefixRepository")
 * @ORM\Table(name="lab_prefix")
 */
class LabPrefix {

    public function __construct() {
        $this->dateUpdated = new \DateTime();
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\column(name="eid_prefix", type="string",length=8)
     */
    protected $eidPrefix;

    /**
     * @ORM\column(name="vl_prefix",type="string",length=8)
     */
    protected $vlPrefix;

    /**
     * @ORM\ManyToOne(targetEntity="plateforme", inversedBy="lab_prefixes")
     * @ORM\JoinColumn(name="plateforme_id", referencedColumnName="id")
     */
    protected $plateforme;

    /**
     * @ORM\Column(type="datetime", name="date_updated")
     * @Assert\NotNull
     */
    protected $dateUpdated;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEidPrefix(): ?string
    {
        return $this->eidPrefix;
    }

    public function setEidPrefix(string $eidPrefix): self
    {
        $this->eidPrefix = $eidPrefix;

        return $this;
    }

    public function getVlPrefix(): ?string
    {
        return $this->vlPrefix;
    }

    public function setVlPrefix(string $vlPrefix): self
    {
        $this->vlPrefix = $vlPrefix;

        return $this;
    }

    public function getDateUpdated(): ?\DateTimeInterface
    {
        return $this->dateUpdated;
    }

    public function setDateUpdated(\DateTimeInterface $dateUpdated): self
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    public function getPlateforme(): ?plateforme
    {
        return $this->plateforme;
    }

    public function setPlateforme(?plateforme $plateforme): self
    {
        $this->plateforme = $plateforme;

        return $this;
    }

   

}
