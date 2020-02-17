<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of EIDTest
 *
 * @author PKom
 * @ORM\Entity(repositoryClass="App\Repository\EIDTestRepository")
 * @ORM\Table(name="eid_test")
 */
class EIDTest {

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
     * @ORM\column(type="string",length=20, unique=true)
     */
    protected $labno;

    /**
     * @ORM\column(type="integer",length=8,name="yearmonth")
     */
    protected $yearmonth;

    /**
     * @ORM\Column(type="datetime", name="collected_date")
     * @Assert\NotNull
     */
    protected $collectedDate;

    /**
     * @ORM\Column(type="datetime", name="received_date")
     * @Assert\NotNull
     */
    protected $receivedDate;

    /**
     * @ORM\Column(type="datetime", name="completed_date")
     * @Assert\NotNull
     */
    protected $completedDate;

    /**
     * @ORM\Column(type="datetime", name="released_date")
     * @Assert\NotNull
     */
    protected $releasedDate;

    /**
     * @ORM\Column(type="datetime", name="dispatched_date",nullable=true)
     */
    protected $dispatchedDate;

    /**
     * @ORM\Column(type="integer", name="infant_age_month",nullable=true)
     */
    protected $infantAgeMonth;

    /**
     * @ORM\Column(type="integer", name="infant_age_week",nullable=true)
     */
    protected $infantAgeWeek;

    /**
     * @ORM\Column(type="integer", name="infant_gender",nullable=true)
     */
    protected $infantGender;

    /**
     * @ORM\Column(type="integer", name="which_pcr",nullable=true)
     */
    protected $WhichPCR;

    /**
     * @ORM\Column(type="integer", name="second_pcr_test_reason",nullable=true)
     */
    protected $secondPCRTestReason;

    /**
     * @ORM\Column(type="integer", name="rejected_reason",nullable=true)
     */
    protected $rejectedReason;

    /**
     * @ORM\column(type="string",length=60,name="pcr_result",nullable=true)
     */
    protected $pcrResult;

    /**
     * @ORM\ManyToOne(targetEntity="EIDPatient", inversedBy="eidTests",cascade={"persist"})
     * @ORM\JoinColumn(name="patient_id", referencedColumnName="id")
     */
    protected $patient;

    /**
     * @ORM\Column(type="integer", name="site_id",nullable=true)
     */
    protected $site;

    /**
     * @ORM\Column(type="integer", name="district_id",nullable=true)
     */
    protected $district;

    /**
     * @ORM\Column(type="integer", name="region_id",nullable=true)
     */
    protected $region;

    /**
     * @ORM\Column(type="integer", name="plateforme_id",nullable=true)
     */
    protected $plateforme;

    /**
     * @ORM\Column(type="integer", name="partner_id",nullable=true)
     */
    protected $partner;

    /**
     * @ORM\Column(type="datetime", name="date_updated")
     * @Assert\NotNull
     */
    protected $dateUpdated;

    public function getId(): ?int {
        return $this->id;
    }

    public function getLabno(): ?string {
        return $this->labno;
    }

    public function setLabno(string $labno): self {
        $this->labno = $labno;

        return $this;
    }

    public function getYearmonth(): ?int {
        return $this->yearmonth;
    }

    public function setYearmonth(int $yearmonth): self {
        $this->yearmonth = $yearmonth;

        return $this;
    }

    public function getCollectedDate(): ?\DateTimeInterface {
        return $this->collectedDate;
    }

    public function setCollectedDate(\DateTimeInterface $collectedDate): self {
        $this->collectedDate = $collectedDate;

        return $this;
    }

    public function getReceivedDate(): ?\DateTimeInterface {
        return $this->receivedDate;
    }

    public function setReceivedDate(\DateTimeInterface $receivedDate): self {
        $this->receivedDate = $receivedDate;

        return $this;
    }

    public function getCompletedDate(): ?\DateTimeInterface {
        return $this->completedDate;
    }

    public function setCompletedDate(\DateTimeInterface $completedDate): self {
        $this->completedDate = $completedDate;

        return $this;
    }

    public function getReleasedDate(): ?\DateTimeInterface {
        return $this->releasedDate;
    }

    public function setReleasedDate(\DateTimeInterface $releasedDate): self {
        $this->releasedDate = $releasedDate;

        return $this;
    }

    public function getDispatchedDate(): ?\DateTimeInterface {
        return $this->dispatchedDate;
    }

    public function setDispatchedDate(?\DateTimeInterface $dispatchedDate): self {
        $this->dispatchedDate = $dispatchedDate;

        return $this;
    }

    public function getInfantAgeMonth(): ?int {
        return $this->infantAgeMonth;
    }

    public function setInfantAgeMonth(?int $infantAgeMonth): self {
        $this->infantAgeMonth = $infantAgeMonth;

        return $this;
    }

    public function getInfantAgeWeek(): ?int {
        return $this->infantAgeWeek;
    }

    public function setInfantAgeWeek(?int $infantAgeWeek): self {
        $this->infantAgeWeek = $infantAgeWeek;

        return $this;
    }

    public function getDateUpdated(): ?\DateTimeInterface {
        return $this->dateUpdated;
    }

    public function setDateUpdated(\DateTimeInterface $dateUpdated): self {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    public function getInfantGender(): ?int {
        return $this->infantGender;
    }

    public function setInfantGender(?int $infantGender): self {
        $this->infantGender = $infantGender;

        return $this;
    }

    public function getWhichPCR(): ?int {
        return $this->WhichPCR;
    }

    public function setWhichPCR(?int $WhichPCR): self {
        $this->WhichPCR = $WhichPCR;

        return $this;
    }

    public function getSecondPCRTestReason(): ?int {
        return $this->secondPCRTestReason;
    }

    public function setSecondPCRTestReason(?int $secondPCRTestReason): self {
        $this->secondPCRTestReason = $secondPCRTestReason;

        return $this;
    }

    public function getRejectedReason(): ?int {
        return $this->rejectedReason;
    }

    public function setRejectedReason(?int $rejectedReason): self {
        $this->rejectedReason = $rejectedReason;

        return $this;
    }

    public function getPcrResult(): ?string {
        return $this->pcrResult;
    }

    public function setPcrResult(?string $pcrResult): self {
        $this->pcrResult = $pcrResult;

        return $this;
    }

    public function getPatient(): ?EIDPatient {
        return $this->patient;
    }

    public function setPatient(?EIDPatient $patient): self {
        $this->patient = $patient;

        return $this;
    }

    public function getSite(): ?int {
        return $this->site;
    }

    public function setSite(?int $site): self {
        $this->site = $site;

        return $this;
    }

    public function getDistrict(): ?int {
        return $this->district;
    }

    public function setDistrict(?int $district): self {
        $this->district = $district;

        return $this;
    }

    public function getRegion(): ?int {
        return $this->region;
    }

    public function setRegion(?int $region): self {
        $this->region = $region;

        return $this;
    }

    public function setPlateforme(?int $plateforme): self {
        $this->plateforme = $plateforme;

        return $this;
    }

    public function getPlateforme(): ?int {
        return $this->plateforme;
    }

    public function getPartner(): ?int {
        return $this->partner;
    }

    public function setPartner(?int $partner): self {
        $this->partner = $partner;

        return $this;
    }

}
