<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of PageVisited
 *
 * @author PKom
 * @ORM\Entity(repositoryClass="App\Repository\PageVisitedRepository")
 * @ORM\Table(name="page_visited")
 */
class PageVisited {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\column(type="string",length=200,name="page")
     */
    protected $page;

    /**
     * @ORM\column(type="integer",name="visited_count")
     */
    protected $visitedCount;

    /**
     * @ORM\column(type="integer",length=8,name="yearmonth")
     */
    protected $yearmonth;

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
     * @ORM\ManyToOne(targetEntity="Visitor")
     * @ORM\JoinColumn(name="visitor_id", referencedColumnName="id")
     */
    protected $visitor;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull
     */
    protected $timestamp;

    public function getId(): ?int {
        return $this->id;
    }

    public function getPage(): ?string {
        return $this->page;
    }

    public function setPage(string $page): self {
        $this->page = $page;

        return $this;
    }

    public function getVisitedCount(): ?int {
        return $this->visitedCount;
    }

    public function setVisitedCount(int $visitedCount): self {
        $this->visitedCount = $visitedCount;

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

    public function getVisitor(): ?visitor {
        return $this->visitor;
    }

    public function setVisitor(?visitor $visitor): self {
        $this->visitor = $visitor;

        return $this;
    }

    public function getYearmonth(): ?int {
        return $this->yearmonth;
    }

    public function setYearmonth(int $yearmonth): self {
        $this->yearmonth = $yearmonth;

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
