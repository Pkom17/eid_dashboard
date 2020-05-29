<?php

#src/Controller/OrganizationController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Utilities\Util;

/**
 * Description of OrganizationController
 *
 * @author PKom
 */
class OrganizationController extends AbstractController {

    /**
     * @Route("/organization", name="app_organization")
     */
    public function index(TranslatorInterface $translator) {
        return $this->indexRegion($translator);
    }

    /**
     * @Route("/organization/region", name="app_org_region")
     */
    public function indexRegion(TranslatorInterface $translator) {
        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();

        return $this->render('organization/region/index.html.twig', [
                    'start' => $translator->trans(Util::MONTHS[substr($start, 4, 2)]) . ' ' . substr($start, 0, 4),
                    'end' => $translator->trans(Util::MONTHS[substr($end, 4, 2)]) . ' ' . substr($end, 0, 4),
        ]);
    }

    /**
     * @Route("/organization/district", name="app_org_district")
     */
    public function indexDistrict(TranslatorInterface $translator) {
        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();

        return $this->render('organization/district/index.html.twig', [
                    'start' => $translator->trans(Util::MONTHS[substr($start, 4, 2)]) . ' ' . substr($start, 0, 4),
                    'end' => $translator->trans(Util::MONTHS[substr($end, 4, 2)]) . ' ' . substr($end, 0, 4),
        ]);
    }

    /**
     * @Route("/organization/site", name="app_org_site")
     */
    public function indexSite(TranslatorInterface $translator) {
        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();

        return $this->render('organization/site/index.html.twig', [
                    'start' => $translator->trans(Util::MONTHS[substr($start, 4, 2)]) . ' ' . substr($start, 0, 4),
                    'end' => $translator->trans(Util::MONTHS[substr($end, 4, 2)]) . ' ' . substr($end, 0, 4),
        ]);
    }

    protected function getStartDateForFilter() {
        $start = $this->get('session')->get("startDate");
        if (null === $start) {
            $start = intval((intval(date("Y")) - 1) . date("m"));
        }
        return $start;
    }

    protected function getEndDateForFilter() {
        $end = $this->get('session')->get("endDate");
        if (null === $end) {
            $end = intval(date("Ym"));
        }
        return $end;
    }

    protected function getAgeInfos(?TranslatorInterface $translator) {
        $agesCategories = $this->getDoctrine()->getRepository(\App\Entity\EIDAgeCategory::class)->getAgesCategories();
        $ages = [];
        $categories = [];
        $k = 0;
        foreach ($agesCategories as $value) {
            $ages[$k]['name'] = $value['name'];
            $categories[] = $translator->trans($value['name']);
            $ages[$k]['limits'][0] = $value['age_min'];
            $ages[$k]['limits'][1] = $value['age_max'];
            $k++;
        }
        $categories[] = $translator->trans('Autres');
        return ['age_categories' => $categories, 'ages' => $ages];
    }

    protected function getTypeOfClinicInfos() {
        $typeOfClinics = $this->getDoctrine()->getRepository(\App\Entity\EIDDictionary::class)->getTypeOfClinic();
        $types = [];
        $k = 0;
        foreach ($typeOfClinics as $value) {
            $types[$k] = $value['entry_name'];
            $k++;
        }
        return $types;
    }

    protected function getMotherHIVStatusInfos() {
        $mothersStatus = $this->getDoctrine()->getRepository(\App\Entity\EIDDictionary::class)->getMotherHIVStatus();
        $status = [];
        $k = 0;
        foreach ($mothersStatus as $value) {
            $status[$k] = $value['entry_name'];
            $k++;
        }
        return $status;
    }

    protected function getMotherRegimentInfos() {
        $motherRegimens = $this->getDoctrine()->getRepository(\App\Entity\EIDDictionary::class)->getMotherRegimen();
        $regimens = [];
        $k = 0;
        foreach ($motherRegimens as $value) {
            $regimens[$k] = $value['entry_name'];
            $k++;
        }
        return $regimens;
    }
    protected function getInfantARVInfos() {
        $infantARVs = $this->getDoctrine()->getRepository(\App\Entity\EIDDictionary::class)->getInfantARV();
        $types = [];
        $categories = [];
        $k = 0;
        foreach ($infantARVs as $value) {
            $types[$k] = $value['entry_name'];
            $k++;
        }
        return $types;
    }

}
