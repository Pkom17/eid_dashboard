<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of SiteContact
 *
 * @author PKom
 * @ORM\Entity(repositoryClass="App\Repository\SiteContactRepository")
 * @ORM\Table(name="site_contact")
 */
class SiteContact {

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
     * @ORM\column(type="string",length=100)
     */
    protected $contact;

    /**
     * @ORM\column(type="string",length=100)
     */
    protected $role;

    /**
     * @ORM\column(type="string",length=100)
     * 
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true )
     * 
     */
    protected $mail;

    /**
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="siteContacts")
     * @ORM\JoinColumn(name="site_id", referencedColumnName="id")
     */
    protected $site;

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

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getSite(): ?site
    {
        return $this->site;
    }

    public function setSite(?site $site): self
    {
        $this->site = $site;

        return $this;
    }

}
