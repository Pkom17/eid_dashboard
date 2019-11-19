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
     * @ORM\column(type="string",length=20)
     */
    protected $dbs_code;

    /**
     * @ORM\column(type="string",length=30)
     */
    protected $patient_code;

    /**
     * @ORM\Column(type="date", name="birth_date")
     */
    protected $birthDate;

    /**
     * @ORM\Column(type="integer", name="gender")
     */
    protected $gender;

    /**
     * @ORM\ManyToOne(targetEntity="EIDDictionary")
     * @ORM\JoinColumn(name="infant_regimen", referencedColumnName="id")
     */
    protected $infantRegimen;

    /**
     * @ORM\ManyToOne(targetEntity="EIDDictionary")
     * @ORM\JoinColumn(name="mother_regimen", referencedColumnName="id")
     */
    protected $motherRegimen;

    /**
     * @ORM\ManyToOne(targetEntity="EIDDictionary")
     * @ORM\JoinColumn(name="mother_hiv_status", referencedColumnName="id")
     */
    protected $motherHivStatus;

    /**  @ORM\Column(type="boolean",nullable=true) */
    protected $infantPTME;

    /**
     * @ORM\ManyToOne(targetEntity="EIDDictionary")
     * @ORM\JoinColumn(name="type_of_clinic", referencedColumnName="id")
     */
    protected $typeOfClinic;

    /**
     * @ORM\ManyToOne(targetEntity="EIDDictionary")
     * @ORM\JoinColumn(name="feeding_type", referencedColumnName="id")
     */
    protected $feedingType;

    /**
     * @ORM\ManyToOne(targetEntity="EIDDictionary")
     * @ORM\JoinColumn(name="stopped_breastfeeding", referencedColumnName="id")
     */
    protected $stoppedBreastfeeding;

    /**  @ORM\Column(type="boolean") */
    protected $infantSymptomatic;

    /**
     * @ORM\ManyToOne(targetEntity="EIDDictionary")
     * @ORM\JoinColumn(name="infant_arv", referencedColumnName="id")
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

    public function setDbsCode(string $dbs_code): self {
        $this->dbs_code = $dbs_code;

        return $this;
    }

    public function getPatientCode(): ?string {
        return $this->patient_code;
    }

    public function setPatientCode(string $patient_code): self {
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

    public function setInfantSymptomatic(bool $infantSymptomatic): self {
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

    public function getGender(): ?dictionary {
        return $this->gender;
    }

    public function setGender(?dictionary $gender): self {
        $this->gender = $gender;

        return $this;
    }

    public function getInfantRegimen(): ?dictionary {
        return $this->infantRegimen;
    }

    public function setInfantRegimen(?dictionary $infantRegimen): self {
        $this->infantRegimen = $infantRegimen;

        return $this;
    }

    public function getMotherRegimen(): ?dictionary {
        return $this->motherRegimen;
    }

    public function setMotherRegimen(?dictionary $motherRegimen): self {
        $this->motherRegimen = $motherRegimen;

        return $this;
    }

    public function getMotherHivStatus(): ?dictionary {
        return $this->motherHivStatus;
    }

    public function setMotherHivStatus(?dictionary $motherHivStatus): self {
        $this->motherHivStatus = $motherHivStatus;

        return $this;
    }

    public function getTypeOfClinic(): ?dictionary {
        return $this->typeOfClinic;
    }

    public function setTypeOfClinic(?dictionary $typeOfClinic): self {
        $this->typeOfClinic = $typeOfClinic;

        return $this;
    }

    public function getFeedingType(): ?dictionary {
        return $this->feedingType;
    }

    public function setFeedingType(?dictionary $feedingType): self {
        $this->feedingType = $feedingType;

        return $this;
    }

    public function getStoppedBreastfeeding(): ?dictionary {
        return $this->stoppedBreastfeeding;
    }

    public function setStoppedBreastfeeding(?dictionary $stoppedBreastfeeding): self {
        $this->stoppedBreastfeeding = $stoppedBreastfeeding;

        return $this;
    }

    public function getInfantARV(): ?dictionary {
        return $this->infantARV;
    }

    public function setInfantARV(?dictionary $infantARV): self {
        $this->infantARV = $infantARV;

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
            $eidTest->setPatient($this);
        }

        return $this;
    }

    public function removeEidTest(EIDTest $eidTest): self
    {
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
