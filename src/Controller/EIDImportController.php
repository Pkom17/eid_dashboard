<?php

#src/Controller/EIDImportController.php

namespace App\Controller;

use App\Entity\EIDImport;
use App\Form\EIDImportType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
Use League\Csv\Reader;

/**
 * Description of EIDImportController
 *
 * @author PKom
 */
class EIDImportController extends AbstractController {

    /**
     * @Route("/eidImport/new", name="app_eid_import_new")
     */
    public function new(Request $request) {

        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();
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
                    $h = $this->readCSV($this->getParameter('eid_import_directory') . "/" . $newFilename);
                } catch (FileException $e) {
                    
                }

                // instead of its contents
                $eidImport->setFileName($newFilename);
                $eidImport->setFileSize($fileSize);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($eidImport);
            $entityManager->flush();
            return new \Symfony\Component\HttpFoundation\Response($h);
            #  return $this->redirect($this->generateUrl('app_eid_import_new'));
        }
        return $this->render('eid_import/new_import.html.twig', [
                    'form' => $form->createView(),
        ]);
    }

    private function readCSV($file) {
        $stream = fopen($file, 'r');
        $reader = Reader::createFromPath($file, 'r');
        $reader->setEnclosure('"');
        $reader->setHeaderOffset(0);
        $reader->skipEmptyRecords();
        $records = $reader->getRecords();
        $records->rewind(); //to the first line
        $rows = iterator_to_array($records, true); // convert content to assoc array
        $data = $this->validData($rows);
        //return $csv->getContent();
        //return $data;
        return implode("", $data[0]);
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
            'AGESEMS' => '','DNA PCR' => '', 'COMPLETED_DATE' => '', 'RELEASED_DATE' => '', 'whichPCR' => '', 'reasonForSecondPCRTest' => '', 'eidInfantPTME' => '', 
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

}
