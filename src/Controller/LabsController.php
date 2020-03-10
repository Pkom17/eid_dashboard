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
class LabsController extends AbstractController {

    /**
     * @Route("/labs", name="app_labs")
     */
    public function index(Request $request, TranslatorInterface $translator) {
        $start = $this->get('session')->get("startDate");
        $start_for_trends = $start;
        $end = $this->get('session')->get("endDate");
        if (null === $start) {
            $start = intval(date("Y") . '01');
            $start_for_trends = intval(intval(date("Y")) - 1 . '01');
        }
        if (null === $end) {
            $end = intval(date("Ym"));
        }
        return $this->render('labs/index.html.twig', [
                    'start' => $translator->trans(Util::MONTHS[substr($start, 4, 2)]) . ' ' . substr($start, 0, 4),
                    'end' => $translator->trans(Util::MONTHS[substr($end, 4, 2)]) . ' ' . substr($end, 0, 4),
        ]);
    }

    /**
     * @Route("/labs/labs_stat", name="app_labs_stats")
     */
    public function labsStat(Request $request, TranslatorInterface $translator) {
        $start = $this->get('session')->get("startDate");
        $end = $this->get('session')->get("endDate");
        if (null === $start) {
            $start = intval(date("Y") . '01');
        }
        if (null === $end) {
            $end = intval(date("Ym"));
        }
        $rows = $this->getDoctrine()->getRepository(\App\Entity\Plateforme::class)->getEidOutcomesLabsStats($start, $end);
        // $plateformes = $this->getDoctrine()->getRepository(\App\Entity\Plateforme::class)->findPlateformes();
        return $this->render('labs/stats.html.twig', [
                    'stats' => $rows,
        ]);
    }

    /**
     * @Route("/labs/labs_stat_age/{which_pcr}", name="app_labs_stats_age",requirements={"which_pcr"="-?\d+"})
     */
    public function labsStatAge(Request $request, TranslatorInterface $translator, int $which_pcr = 0) {
        $start = $this->get('session')->get("startDate");
        $end = $this->get('session')->get("endDate");
        if (null === $start) {
            $start = intval(date("Y") . '01');
        }
        if (null === $end) {
            $end = intval(date("Ym"));
        }
        $rows = $this->getDoctrine()->getRepository(\App\Entity\Plateforme::class)->getEidOutcomesLabsAge($which_pcr, $start, $end);
        $plateformes = $this->getDoctrine()->getRepository(\App\Entity\Plateforme::class)->findPlateformes();
        $agesCategories = $this->getDoctrine()->getRepository(\App\Entity\EIDAgeCategory::class)->getAgesCategories();
        $d = [];
        $labs = [];
        $ages = [];
        $ageCats = [];
        foreach ($plateformes as $v) {
            $labs[] = $v['name'];
        }

        $k = 0;
        foreach ($agesCategories as $value) {
            $ages[$k]['name'] = $value['name'];
            $ageCats[] = $translator->trans($value['name']);
            $ages[$k]['limits'][0] = $value['age_min'];
            $ages[$k]['limits'][1] = $value['age_max'];
            $k++;
        }

        $u = 0;
        foreach ($labs as $lab) {

            $v = 0;
            foreach ($ages as $age) {
                $d[$v]['name'] = $lab;
                $d[$v]['stack'] = $translator->trans($age['name']);
                $d[$v]['type'] = 'column';
                // $d[$v]['yAxis'] = 1;
                foreach ($rows as $row) {
                    if ($row['age_month'] >= $age['limits'][0] && $row['age_month'] < $age['limits'][1]) {
//                        $d[0]['data'][$u] += intval($entry['positif']);
//                        $d[1]['data'][$u] += intval($entry['negatif']);
//                        $d[2]['data'][$u] += intval($entry['invalide']);
                    }
                }
                $v++;
            }
            $u++;
        }



//        echo '<pre>';
//        print_r($d);
//        die();

        return $this->render('labs/stats_age.html.twig', [
                    'stats' => $rows,
        ]);
    }

    /**
     * @Route("/labs/labs_tat", name="app_labs_tat")
     */
    public function labsTAT(Request $request, TranslatorInterface $translator) {
        $start = $this->get('session')->get("startDate");
        $end = $this->get('session')->get("endDate");
        if (null === $start) {
            $start = intval(date("Y") . '01');
        }
        if (null === $end) {
            $end = intval(date("Ym"));
        }
        $rows = $this->getDoctrine()->getRepository(\App\Entity\Plateforme::class)->getEidOutcomesLabsStats($start, $end);
        // $plateformes = $this->getDoctrine()->getRepository(\App\Entity\Plateforme::class)->findPlateformes();
        $data = '';
        return $this->render('labs/labs_tat.html.twig', [
                   // 'stats' => $rows,
        ]);
    }

}
