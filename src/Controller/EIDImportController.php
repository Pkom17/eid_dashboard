<?php

#src/Controller/EIDImportController.php

namespace App\Controller;

use App\Entity\EIDImport;
use App\Entity\EIDDictionary;
use App\Entity\Site;
use App\Entity\EIDPatient;
use App\Entity\EIDTest;
use App\Form\EIDImportType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
Use League\Csv\Reader;

/**
 * Description of EIDImportController
 *
 * @author PKom
 */
class EIDImportController extends AbstractController {

    protected $dictionaryMap = array();
    protected $sitesMap = array();
    protected $districtsMap = array();
    protected $regionsMap = array();
    protected $partnersMap = array();
    protected $labsMap = array();
    protected $badRows = 0;
    private $em;

    /**
     * @Route("/eidImport/new", name="app_eid_import_new")
     */
    public function new(Request $request) {

        $this->denyAccessUnlessGranted('ROLE_USER');
        $this->em = $this->getDoctrine()->getManager();
        $user = $this->em->find('App\Entity\User', $this->getUser()->getId());
        $eidImport = new EIDImport();
        $eidImport->setUser($user);
        $eidImport->setDateImport(new \DateTime());
        $form = $this->createForm(EIDImportType::class, $eidImport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $exportFile = $form['file']->getData();
            if ($exportFile) {
                $originalFilename = pathinfo($exportFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $exportFile->guessExtension();
                $fileSize = $exportFile->getSize();
                try {
                    $exportFile->move(
                            $this->getParameter('eid_import_directory'),
                            $newFilename
                    );
                    $this->readCSV($this->getParameter('eid_import_directory') . "/" . $newFilename);
                } catch (FileException $e) {
                    print_r($e);
                }
                $eidImport->setFileName($newFilename);
                $eidImport->setFileSize($fileSize);
            }
            $this->em->persist($eidImport);
            $this->em->flush();
            $this->em->clear();

            //everything looks fine
            $this->addFlash(
                    'success',
                    'Insertion done !' . $this->badRows
            );
        }
        return $this->render('eid_import/new_import.html.twig', [
                    'form' => $form->createView(),
        ]);
    }

    private function readCSV($file) {
        $reader = Reader::createFromPath($file, 'r');
        $reader->setEnclosure('"');
        $reader->setHeaderOffset(0);
        $reader->skipEmptyRecords();
        $records = $reader->getRecords();
        $records->rewind(); //to the first line
        $rows = iterator_to_array($records, true); // convert content to assoc array
        $data = $this->validData($rows);
        $this->em = $this->getDoctrine()->getManager();
        $this->loadDictionaryInfo();
        $this->loadSitesInfo();
        $this->loadLabsInfo();
//        $i = 0;
        foreach ($data as $row_data) {
//            $i++;
            if (trim($row_data['LABNO']) === '' || trim($row_data['SUJETNO']) === '') {
                $this->badRows++;
                continue;
            }
            $this->insertCSVData($row_data);
        }
        $this->em->flush();
    }

    private function insertCSVData($row_data) {
        $this->insertOrUpdateEIDTestFromEntryData($row_data);
    }

    private function getPatientFromEntryData($row_data): ?EIDPatient {
        $patient = $this->getDoctrine()->getRepository(EIDPatient::class)->findOneByDBSCode($row_data['SUJETNO']);
        if (is_null($patient)) {
            $patient = $this->createPatientFromEntryData($row_data);
        }
        $patient->setLastTest(\DateTime::createFromFormat('d/m/Y H:i', $row_data['RELEASED_DATE']));
        return $patient;
    }

    private function createPatientFromEntryData($row_data): ?EIDPatient {
        $eid_patient = new EIDPatient();
        $birthDate = \DateTime::createFromFormat('d/m/Y', $row_data['DATENAIS']);
        $eid_patient->setBirthDate($birthDate);
        $eid_patient->setDbsCode($row_data['SUJETNO']);
        $eid_patient->setFeedingType($this->checkKey($this->dictionaryMap, EIDDictionary::FEEDING_TYPE . $row_data['eidHowChildFed']));
        $eid_patient->setGender(EIDDictionary::resolveGender($row_data['SEXE']));
        $eid_patient->setInfantARV($this->checkKey($this->dictionaryMap, EIDDictionary::EID_INFANT_ARV . $row_data['eidInfantsARV']));
        $eid_patient->setInfantCotrimoxazole(EIDDictionary::resolveInfantCotrimoxazole($row_data['eidInfantCotrimoxazole']));
        $eid_patient->setInfantPTME(EIDDictionary::resolveInfantPTME($row_data['eidInfantPTME']));
        $eid_patient->setInfantSymptomatic(EIDDictionary::resolveInfantSymptomatic($row_data['eidInfantSymptomatic']));
        $eid_patient->setMotherHivStatus($this->checkKey($this->dictionaryMap, EIDDictionary::HIV_STATUS . $row_data['eidMothersHIVStatus']));
        $eid_patient->setMotherRegimen($this->checkKey($this->dictionaryMap, EIDDictionary::EID_MOTHER_ARV . $row_data['eidMothersARV']));
        $eid_patient->setPatientCode($row_data['SUJETSIT']);
        $eid_patient->setStoppedBreastfeeding($this->checkKey($this->dictionaryMap, EIDDictionary::STOP_BREASTFEEDING . $row_data['eidStoppedBreastfeeding']));
        $eid_patient->setTypeOfClinic($this->checkKey($this->dictionaryMap, EIDDictionary::TYPE_OF_CLINIC . $row_data['eidTypeOfClinic']));
        $eid_patient->setLastTest(\DateTime::createFromFormat('d/m/Y H:i', $row_data['RELEASED_DATE']));
        $this->em->persist($eid_patient);
        $this->em->flush();
        return $eid_patient;
    }

    private function insertOrUpdateEIDTestFromEntryData($row_data) {
        $eid_test = $this->getDoctrine()->getRepository(EIDTest::class)->findOneBy(["labno" => $row_data['LABNO']]);
        if (is_null($eid_test)) {
            $eid_test = $this->createEIDTestFromEntryData($row_data);
        }
        $releasedDate = \DateTime::createFromFormat('d/m/Y H:i', $row_data['RELEASED_DATE']);
        $yearMonth = $releasedDate->format('Ym');
        $eid_test->setYearmonth($yearMonth);
        $eid_test->setCollectedDate(\DateTime::createFromFormat('d/m/Y H:i', $row_data['DINTV']));
        $eid_test->setReceivedDate(\DateTime::createFromFormat('d/m/Y H:i', $row_data['DRCPT']));
        $eid_test->setCompletedDate(\DateTime::createFromFormat('d/m/Y H:i', $row_data['COMPLETED_DATE']));
        $eid_test->setReleasedDate($releasedDate);
        $eid_test->setDispatchedDate(null);
        $eid_test->setInfantAgeMonth($row_data['AGEMOIS']);
        $eid_test->setInfantAgeWeek($row_data['AGESEMS']);
        $eid_test->setInfantGender(EIDDictionary::resolveGender($row_data['SEXE']));
        $eid_test->setWhichPCR(EIDDictionary::resolveWhichPCR($row_data['whichPCR']));
        $eid_test->setSecondPCRTestReason($this->checkKey($this->dictionaryMap, EIDDictionary::REASON_FOR_SECOND_PCR . $row_data['reasonForSecondPCRTest']));
        $eid_test->setRejectedReason(null);
        $eid_test->setPcrResult(\App\Entity\EIDDictionary::resolvePCRResultFromDataEntry($row_data['DNA PCR']));
        $site_id = $this->checkKey($this->sitesMap, $row_data['CODE_SITE_DATIM']);
        $eid_test->setSite($site_id);
        $district_id = $this->checkKey($this->districtsMap, $row_data['CODE_SITE_DATIM']);
        $eid_test->setDistrict($district_id);
        $partner_id = $this->checkKey($this->partnersMap, $row_data['CODE_SITE_DATIM']);
        $eid_test->setPartner($partner_id);
        $region_id = $this->checkKey($this->regionsMap, $row_data['CODE_SITE_DATIM']);
        $eid_test->setRegion($region_id);
        $plateforme_id = $this->checkKey($this->labsMap, substr($row_data['LABNO'], 0, 4));
        $eid_test->setPlateforme($plateforme_id);
        $eid_test->setPatient($this->getPatientFromEntryData($row_data));
        $this->em->persist($eid_test);
        unset($eid_test);
    }

    private function createEIDTestFromEntryData($row_data) {
        $eid_test = new EIDTest();
        $releasedDate = \DateTime::createFromFormat('d/m/Y H:i', $row_data['RELEASED_DATE']);
        $yearMonth = $releasedDate->format('Ym');
        $eid_test->setYearmonth($yearMonth);
        $eid_test->setLabno($row_data['LABNO']);
        $eid_test->setCollectedDate(\DateTime::createFromFormat('d/m/Y H:i', $row_data['DINTV']));
        $eid_test->setReceivedDate(\DateTime::createFromFormat('d/m/Y H:i', $row_data['DRCPT']));
        $eid_test->setCompletedDate(\DateTime::createFromFormat('d/m/Y H:i', $row_data['COMPLETED_DATE']));
        $eid_test->setReleasedDate($releasedDate);
        $eid_test->setDispatchedDate(null);
        $eid_test->setInfantAgeMonth($row_data['AGEMOIS']);
        $eid_test->setInfantAgeWeek($row_data['AGESEMS']);
        $eid_test->setInfantGender(EIDDictionary::resolveGender($row_data['SEXE']));
        $eid_test->setWhichPCR(EIDDictionary::resolveWhichPCR($row_data['whichPCR']));
        $eid_test->setSecondPCRTestReason($this->checkKey($this->dictionaryMap, EIDDictionary::REASON_FOR_SECOND_PCR . $row_data['reasonForSecondPCRTest']));
        $eid_test->setRejectedReason(null);
        $eid_test->setPcrResult(\App\Entity\EIDDictionary::resolvePCRResultFromDataEntry($row_data['DNA PCR']));
        $site_id = $this->checkKey($this->sitesMap, $row_data['CODE_SITE_DATIM']);
        $eid_test->setSite($site_id);
        $district_id = $this->checkKey($this->districtsMap, $row_data['CODE_SITE_DATIM']);
        $eid_test->setDistrict($district_id);
        $partner_id = $this->checkKey($this->partnersMap, $row_data['CODE_SITE_DATIM']);
        $eid_test->setPartner($partner_id);
        $region_id = $this->checkKey($this->regionsMap, $row_data['CODE_SITE_DATIM']);
        $eid_test->setRegion($region_id);
        $plateforme_id = $this->checkKey($this->labsMap, substr($row_data['LABNO'], 0, 4));
        $eid_test->setPlateforme($plateforme_id);
        $eid_test->setPatient($this->getPatientFromEntryData($row_data));
        $this->em->persist($eid_test);
        return $eid_test;
    }

    private function validData($csvData) {
        if (!is_array($csvData)) {
            return false;
        }
        $toRemove = ['ECHSTAT' => '', 'ETUDE' => '', 'CODE_SITE' => '', 'NOM_SITE' => '', 'NOM_SITE_DATIM' => '', 'AGEANS' => '',
            'STARTED_DATE' => '', 'NOMPREV' => '', 'NOMMED' => '',
        ];
        $toLookFor = [
            'LABNO' => '', 'SUJETNO' => '', 'SUJETSIT' => '', 'DRCPT' => '', 'DINTV' => '', 'CODE_SITE_DATIM' => '', 'SEXE' => '', 'DATENAIS' => '', 'AGEMOIS' => '',
            'AGESEMS' => '', 'DNA PCR' => '', 'COMPLETED_DATE' => '', 'RELEASED_DATE' => '', 'whichPCR' => '', 'reasonForSecondPCRTest' => '', 'eidInfantPTME' => '',
            'eidTypeOfClinic' => '', 'eidInfantSymptomatic' => '', 'eidMothersHIVStatus' => '', 'eidMothersARV' => '', 'eidInfantsARV' => '', 'eidInfantCotrimoxazole' => '',
            'eidHowChildFed' => '', 'eidStoppedBreastfeeding' => '',
        ];
        foreach ($csvData as $v) {
            $d[] = array_diff_key($v, $toRemove);
        }
        if (!isset($d[0]) || count(array_diff_key($toLookFor, $d[0])) != 0) {
            return false;
        }
        return $d;
    }

    private function loadDictionaryInfo() {
        $infos = $this->getDoctrine()->getRepository(EIDDictionary::class)->findDictionaryInfo();
        $map = [];
        if (is_array($infos)) {
            foreach ($infos as $value) {
                $map[$value['domain_code'] . $value['entry_code']] = $value['id'];
            }
            $this->dictionaryMap = $map;
        }
    }

    private function loadSitesInfo() {
        $infos = $this->getDoctrine()->getRepository(Site::class)->findSites();
        if (is_array($infos)) {
            foreach ($infos as $value) {
                $this->sitesMap[$value['datim_code']] = $value['id'];
                $this->districtsMap[$value['datim_code']] = $value['district_id'];
                $this->regionsMap[$value['datim_code']] = $value['region_id'];
                $this->partnersMap[$value['datim_code']] = $value['partner_id'];
            }
        }
    }

    private function loadLabsInfo() {
        $infos = $this->getDoctrine()->getRepository(\App\Entity\LabPrefix::class)->findLabs();
        if (is_array($infos)) {
            foreach ($infos as $value) {
                $this->labsMap[$value['eid_prefix']] = $value['plateforme_id'];
            }
        }
    }

    private function checkKey($map, $key) {
        return (array_key_exists($key, $map)) ? $map[$key] : null;
    }

}
