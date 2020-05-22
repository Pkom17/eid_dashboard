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

}
