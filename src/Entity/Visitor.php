<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of Visitor
 *
 * @author PKom
 * @ORM\Entity(repositoryClass="App\Repository\VisitorRepository")
 * @ORM\Table(name="visitor")
 */
class Visitor {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\column(type="integer",name="visited_count")
     */
    protected $visitedCount;

    /**
     * @ORM\column(type="integer",length=8,name="yearmonth")
     */
    protected $yearmonth;

    /**
     * @ORM\column(type="string",length=80,name="address")
     */
    protected $IPAddress;

    /**
     * @ORM\Column(type="datetime", name="first_visited_date")
     * @Assert\NotNull
     */
    protected $firstVisitedDate;

    /**
     * @ORM\Column(type="datetime", name="last_visited_date")
     * @Assert\NotNull
     */
    protected $lastVisitedDate;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull
     */
    protected $timestamp;

    public function __construct() {
        $this->siteContacts = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getVisitedCount(): ?int {
        return $this->visitedCount;
    }

    public function setVisitedCount(int $visitedCount): self {
        $this->visitedCount = $visitedCount;

        return $this;
    }

    public function getYearmonth(): ?int {
        return $this->yearmonth;
    }

    public function setYearmonth(int $yearmonth): self {
        $this->yearmonth = $yearmonth;

        return $this;
    }

    public function getIPAddress(): ?string {
        return $this->IPAddress;
    }

    public function setIPAddress(string $IPAddress): self {
        $this->IPAddress = $IPAddress;

        return $this;
    }

    public function getFirstVisitedDate(): ?\DateTimeInterface {
        return $this->firstVisitedDate;
    }

    public function setFirstVisitedDate(\DateTimeInterface $firstVisitedDate): self {
        $this->firstVisitedDate = $firstVisitedDate;

        return $this;
    }

    public function getLastVisitedDate(): ?\DateTimeInterface {
        return $this->lastVisitedDate;
    }

    public function setLastVisitedDate(\DateTimeInterface $lastVisitedDate): self {
        $this->lastVisitedDate = $lastVisitedDate;

        return $this;
    }

    public function getTimestamp(): ?int {
        return $this->timestamp;
    }

    public function setTimestamp(int $timestamp): self {
        $this->timestamp = $timestamp;

        return $this;
    }

}
