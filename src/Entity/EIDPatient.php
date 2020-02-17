<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of EIDPatient
 *
 * @author PKom
 * @ORM\Entity(repositoryClass="App\Repository\EIDPatientRepository")
 * @ORM\Table(name="eid_patient")
 */
class EIDPatient {

    public function __construct() {
        $this->dateUpdated = new \DateTime();
        $this->eidTests = new ArrayCollection();
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\column(type="string",length=20,nullable=true)
     */
    protected $dbs_code;

    /**
     * @ORM\column(type="string",length=60,nullable=true)
     */
    protected $patient_code;

    /**
     * @ORM\Column(type="date", name="birth_date",nullable=true)
     */
    protected $birthDate;

    /**
     * @ORM\Column(type="integer", name="gender",nullable=true)
     */
    protected $gender;

    /**
     * @ORM\Column(type="integer", name="mother_regimen",nullable=true)
     */
    protected $motherRegimen;

    /**
     * @ORM\Column(type="integer", name="mother_hiv_status",nullable=true)
     */
    protected $motherHivStatus;

    /**  @ORM\Column(type="boolean",nullable=true) */
    protected $infantPTME;

    /**
     * @ORM\Column(type="integer", name="type_of_clinic",nullable=true)
     */
    protected $typeOfClinic;

    /**
     * @ORM\Column(type="integer", name="feeding_type",nullable=true)
     */
    protected $feedingType;

    /**
     * @ORM\Column(type="integer", name="stopped_breastfeeding",nullable=true)
     */
    protected $stoppedBreastfeeding;

    /**  @ORM\Column(type="boolean",nullable=true) */
    protected $infantSymptomatic;

    /**
     * @ORM\Column(type="integer", name="infant_arv",nullable=true)
     */
    protected $infantARV;

    /**  @ORM\Column(type="boolean",nullable=true) */
    protected $infantCotrimoxazole;

    /**
     * @ORM\Column(type="date", name="last_test")
     */
    protected $lastTest;

    /**
     * @ORM\Column(type="datetime", name="date_updated")
     */
    protected $dateUpdated;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EIDTest", mappedBy="patient")
     */
    private $eidTests;

    public function getId(): ?int {
        return $this->id;
    }

    public function getDbsCode(): ?string {
        return $this->dbs_code;
    }

    public function setDbsCode(?string $dbs_code): self {
        $this->dbs_code = $dbs_code;

        return $this;
    }

    public function getPatientCode(): ?string {
        return $this->patient_code;
    }

    public function setPatientCode(?string $patient_code): self {
        $this->patient_code = $patient_code;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getInfantPTME(): ?bool {
        return $this->infantPTME;
    }

    public function setInfantPTME(?bool $infantPTME): self {
        $this->infantPTME = $infantPTME;

        return $this;
    }

    public function getInfantSymptomatic(): ?bool {
        return $this->infantSymptomatic;
    }

    public function setInfantSymptomatic(?bool $infantSymptomatic): self {
        $this->infantSymptomatic = $infantSymptomatic;

        return $this;
    }

    public function getInfantCotrimoxazole(): ?bool {
        return $this->infantCotrimoxazole;
    }

    public function setInfantCotrimoxazole(?bool $infantCotrimoxazole): self {
        $this->infantCotrimoxazole = $infantCotrimoxazole;

        return $this;
    }

    public function getLastTest(): ?\DateTimeInterface {
        return $this->lastTest;
    }

    public function setLastTest(\DateTimeInterface $lastTest): self {
        $this->lastTest = $lastTest;

        return $this;
    }

    public function getDateUpdated(): ?\DateTimeInterface {
        return $this->dateUpdated;
    }

    public function setDateUpdated(\DateTimeInterface $dateUpdated): self {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    public function getGender(): ?int {
        return $this->gender;
    }

    public function setGender(?int $gender): self {
        $this->gender = $gender;

        return $this;
    }

    public function getMotherRegimen(): ?int {
        return $this->motherRegimen;
    }

    public function setMotherRegimen(?int $motherRegimen): self {
        $this->motherRegimen = $motherRegimen;

        return $this;
    }

    public function getMotherHivStatus(): ?int {
        return $this->motherHivStatus;
    }

    public function setMotherHivStatus(?int $motherHivStatus): self {
        $this->motherHivStatus = $motherHivStatus;

        return $this;
    }

    public function getTypeOfClinic(): ?int {
        return $this->typeOfClinic;
    }

    public function setTypeOfClinic(?int $typeOfClinic): self {
        $this->typeOfClinic = $typeOfClinic;

        return $this;
    }

    public function getFeedingType(): ?int {
        return $this->feedingType;
    }

    public function setFeedingType(?int $feedingType): self {
        $this->feedingType = $feedingType;

        return $this;
    }

    public function getStoppedBreastfeeding(): ?int {
        return $this->stoppedBreastfeeding;
    }

    public function setStoppedBreastfeeding(?int $stoppedBreastfeeding): self {
        $this->stoppedBreastfeeding = $stoppedBreastfeeding;

        return $this;
    }

    public function getInfantARV(): ?int {
        return $this->infantARV;
    }

    public function setInfantARV(?int $infantARV): self {
        $this->infantARV = $infantARV;

        return $this;
    }

    /**
     * @return Collection|EIDTest[]
     */
    public function getEidTests(): Collection {
        return $this->eidTests;
    }

    public function addEidTest(EIDTest $eidTest): self {
        if (!$this->eidTests->contains($eidTest)) {
            $this->eidTests[] = $eidTest;
            $eidTest->setPatient($this);
        }

        return $this;
    }

    public function removeEidTest(EIDTest $eidTest): self {
        if ($this->eidTests->contains($eidTest)) {
            $this->eidTests->removeElement($eidTest);
            // set the owning side to null (unless already changed)
            if ($eidTest->getPatient() === $this) {
                $eidTest->setPatient(null);
            }
        }

        return $this;
    }

}
