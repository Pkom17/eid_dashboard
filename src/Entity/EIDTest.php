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
     * @ORM\column(type="string",length=20)
     */
    protected $labno;

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
     * @ORM\Column(type="integer", name="infant_age_month")
     */
    protected $infantAgeMonth;

    /**
     * @ORM\Column(type="integer", name="infant_age_week")
     */
    protected $infantAgeWeek;

    /**
     * @ORM\Column(type="integer", name="infant_gender")
     */
    protected $infantGender;

    /**
     * @ORM\ManyToOne(targetEntity="EIDDictionary")
     * @ORM\JoinColumn(name="which_pcr", referencedColumnName="id")
     */
    protected $WhichPCR;

    /**
     * @ORM\ManyToOne(targetEntity="EIDDictionary")
     * @ORM\JoinColumn(name="second_pcr_test_reason", referencedColumnName="id")
     */
    protected $secondPCRTestReason;

    /**
     * @ORM\ManyToOne(targetEntity="EIDDictionary")
     * @ORM\JoinColumn(name="rejected_reason", referencedColumnName="id")
     */
    protected $rejectedReason;

    /**
     * @ORM\column(type="string",length=60,name="pcr_result")
     */
    protected $pcrResult;

    /**
     * @ORM\ManyToOne(targetEntity="EIDPatient", inversedBy="eidTests")
     * @ORM\JoinColumn(name="patient_id", referencedColumnName="id")
     */
    protected $patient;

    /**
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="eidTests")
     * @ORM\JoinColumn(name="site_id", referencedColumnName="id")
     */
    protected $site;

    /**
     * @ORM\ManyToOne(targetEntity="District", inversedBy="eidTests")
     * @ORM\JoinColumn(name="district_id", referencedColumnName="id")
     */
    protected $district;

    /**
     * @ORM\ManyToOne(targetEntity="Region", inversedBy="eidTests")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id")
     */
    protected $region;

    /**
     * @ORM\ManyToOne(targetEntity="Partner", inversedBy="eidTests")
     * @ORM\JoinColumn(name="partner_id", referencedColumnName="id")
     */
    protected $partner;

    /**
     * @ORM\Column(type="datetime", name="date_updated")
     * @Assert\NotNull
     */
    protected $dateUpdated;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabno(): ?string
    {
        return $this->labno;
    }

    public function setLabno(string $labno): self
    {
        $this->labno = $labno;

        return $this;
    }

    public function getCollectedDate(): ?\DateTimeInterface
    {
        return $this->collectedDate;
    }

    public function setCollectedDate(\DateTimeInterface $collectedDate): self
    {
        $this->collectedDate = $collectedDate;

        return $this;
    }

    public function getReceivedDate(): ?\DateTimeInterface
    {
        return $this->receivedDate;
    }

    public function setReceivedDate(\DateTimeInterface $receivedDate): self
    {
        $this->receivedDate = $receivedDate;

        return $this;
    }

    public function getCompletedDate(): ?\DateTimeInterface
    {
        return $this->completedDate;
    }

    public function setCompletedDate(\DateTimeInterface $completedDate): self
    {
        $this->completedDate = $completedDate;

        return $this;
    }

    public function getReleasedDate(): ?\DateTimeInterface
    {
        return $this->releasedDate;
    }

    public function setReleasedDate(\DateTimeInterface $releasedDate): self
    {
        $this->releasedDate = $releasedDate;

        return $this;
    }

    public function getDispatchedDate(): ?\DateTimeInterface
    {
        return $this->dispatchedDate;
    }

    public function setDispatchedDate(?\DateTimeInterface $dispatchedDate): self
    {
        $this->dispatchedDate = $dispatchedDate;

        return $this;
    }

    public function getInfantAgeMonth(): ?int
    {
        return $this->infantAgeMonth;
    }

    public function setInfantAgeMonth(int $infantAgeMonth): self
    {
        $this->infantAgeMonth = $infantAgeMonth;

        return $this;
    }

    public function getInfantAgeWeek(): ?int
    {
        return $this->infantAgeWeek;
    }

    public function setInfantAgeWeek(int $infantAgeWeek): self
    {
        $this->infantAgeWeek = $infantAgeWeek;

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

    public function getInfantGender(): ?EIDDictionary
    {
        return $this->infantGender;
    }

    public function setInfantGender(?EIDDictionary $infantGender): self
    {
        $this->infantGender = $infantGender;

        return $this;
    }

    public function getWhichPCR(): ?EIDDictionary
    {
        return $this->WhichPCR;
    }

    public function setWhichPCR(?EIDDictionary $WhichPCR): self
    {
        $this->WhichPCR = $WhichPCR;

        return $this;
    }

    public function getSecondPCRTestReason(): ?EIDDictionary
    {
        return $this->secondPCRTestReason;
    }

    public function setSecondPCRTestReason(?EIDDictionary $secondPCRTestReason): self
    {
        $this->secondPCRTestReason = $secondPCRTestReason;

        return $this;
    }

    public function getRejectedReason(): ?EIDDictionary
    {
        return $this->rejectedReason;
    }

    public function setRejectedReason(?EIDDictionary $rejectedReason): self
    {
        $this->rejectedReason = $rejectedReason;

        return $this;
    }

    public function getPcrResult(): ?EIDDictionary
    {
        return $this->pcrResult;
    }

    public function setPcrResult(?EIDDictionary $pcrResult): self
    {
        $this->pcrResult = $pcrResult;

        return $this;
    }

    public function getPatient(): ?EIDPatient
    {
        return $this->patient;
    }

    public function setPatient(?EIDPatient $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

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

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

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
    


   

}
