<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of EIDDictionary
 *
 * @author PKom
 * @ORM\Entity(repositoryClass="App\Repository\EIDDictionaryRepository")
 * @ORM\Table(name="eid_dictionary")
 */
class EIDDictionary {
# domains  

    public const REASON_FOR_SECOND_PCR = 1;
    public const HIV_STATUS = 2;
    public const FEEDING_TYPE = 3;
    public const STOP_BREASTFEEDING = 4;
    public const TYPE_OF_CLINIC = 5;
    public const EID_INFANT_ARV = 6;
    public const EID_MOTHER_ARV = 7;
    public const REJECTED_REASON = 8;
    public const PCR_RESULT = 9;
    public const GENDER = 10;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\column(type="string",length=20,name="entry_code",nullable=true)
     */
    protected $entryCode;

    /**
     * @ORM\column(type="string",length=120,name="entry_name")
     */
    protected $entryName;

    /**
     * @ORM\column(type="string",length=120,name="entry_trans")
     */
    protected $entryTrans;

    /**
     * @ORM\column(type="smallint",name="domain_code")
     */
    protected $domainCode;

    /**
     * @ORM\column(type="string",length=60,name="domain_name")
     */
    protected $domainName;

    public function getId(): ?int {
        return $this->id;
    }

    public function getEntryName(): ?string {
        return $this->entryName;
    }

    public function setEntryName(string $entryName): self {
        $this->entryName = $entryName;

        return $this;
    }

    public function getEntryTrans(): ?string {
        return $this->entryTrans;
    }

    public function setEntryTrans(string $entryTrans): self {
        $this->entryTrans = $entryTrans;

        return $this;
    }

    public function getDomain(): ?string {
        return $this->domain;
    }

    public function setDomain(string $domain): self {
        $this->domain = $domain;

        return $this;
    }

    public static function resolvePCRResultFromDataEntry($entry) {
        if (is_null($entry) || trim($entry)=='') {
            return "Négatif";
        }
        $entry_lower = strtolower($entry);
        $pattern_pos = '/positi.*/';
        $pattern_neg = '/n.*gati.*/';
        if (preg_match($pattern_neg, $entry_lower)) {
            return "Négatif";
        } elseif (preg_match($pattern_pos, $entry_lower)) {
            return "Positif";
        } else {
            return "Invalide";
        }
    }

    public static function resolveGender($entry) {
        if (is_null($entry) || !empty($entry) || ($entry != 1 && $entry != 2)) {
            return 0;
        }
        return intval($entry);
    }

    public static function resolveWhichPCR($entry) {
        if (is_null($entry) || empty($entry) || ($entry != 1 && $entry != 2)) {
            return null;
        }
        return intval($entry);
    }

    public static function resolveInfantCotrimoxazole($entry) {
        if (intval($entry) == 1) {
            return true;
        } elseif (intval($entry) == 2) {
            return false;
        } else{
            return null;
        }
    }
    public static function resolveInfantPTME($entry) {
        if (intval($entry) == 1) {
            return true;
        } elseif (intval($entry) == 2) {
            return false;
        } else{
            return null;
        }
    }
    public static function resolveInfantSymptomatic($entry) {
        if (intval($entry) == 1) {
            return true;
        } elseif (intval($entry) == 2) {
            return false;
        } else{
            return null;
        }
    }

}
