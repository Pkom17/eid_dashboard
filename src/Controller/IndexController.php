<?php

#src/Controller/IndexController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Utilities\Util;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Description of IndexController
 *
 * @author PKom
 */
class IndexController extends AbstractController {

    private function getStartDateForFilter() {
        $start = $this->get('session')->get("startDate");
        if (null === $start) {
            $start = intval((intval(date("Y")) - 1) . date("m"));
        }
        return $start;
    }

    private function getStartDateForTrendsFilter() {
        $start_for_trends = $this->get('session')->get("startDate");
        if (null === $start_for_trends) {
            $start_for_trends = intval(intval(date("Y")) - 1 . '01');
        }
        return $start_for_trends;
    }

    private function getEndDateForFilter() {
        $end = $this->get('session')->get("endDate");
        if (null === $end) {
            $end = intval(date("Ym"));
        }
        return $end;
    }

    /**
     * @Route("/", name="app_home")
     */
    public function index(Request $request, TranslatorInterface $translator) {
        $start = $this->getStartDateForFilter();
        $start_for_trends = $this->getStartDateForTrendsFilter();
        $end = $this->getEndDateForFilter();
        return $this->render('home/index.html.twig', [
                    'start' => $translator->trans(Util::MONTHS[substr($start, 4, 2)]) . ' ' . substr($start, 0, 4),
                    'start_for_trends' => $translator->trans(Util::MONTHS[substr($start_for_trends, 4, 2)]) . ' ' . substr($start_for_trends, 0, 4),
                    'end' => $translator->trans(Util::MONTHS[substr($end, 4, 2)]) . ' ' . substr($end, 0, 4),
        ]);
    }

    /**
     * @Route("/filter", name="app_filter")
     */
    public function filter(Request $request, TranslatorInterface $translator) {
        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        return $this->render('includes/filter.html.twig', [
                    'start' => $translator->trans(Util::MONTHS[substr($start, 4, 2)]) . ' ' . substr($start, 0, 4),
                    'end' => $translator->trans(Util::MONTHS[substr($end, 4, 2)]) . ' ' . substr($end, 0, 4),
        ]);
    }

    /**
     * @Route("/org_filter", name="app_filter")
     */
    public function orgFilter(Request $request) {
        $regions = $this->getDoctrine()->getRepository(\App\Entity\Region::class)->findRegions();
        return $this->render('includes/org_filter.html.twig', [
                    'regions' => $regions,
        ]);
    }

    /**
     * @Route("/get_districts/{region}", name="app_get_districts_by_region",requirements={"region"="-?\d+"})
     */
    public function getDistrictsByRegion(Request $request, $region) {
        $districts = $this->getDoctrine()->getRepository(\App\Entity\District::class)->findDistrictsByRegion($region);
        return new \Symfony\Component\HttpFoundation\JsonResponse($districts);
    }

    /**
     * @Route("/get_sites/{district}", name="app_get_sites_by_district",requirements={"district"="-?\d+"})
     */
    public function getSitesByDistrict(Request $request, $district) {
        $sites = $this->getDoctrine()->getRepository(\App\Entity\Site::class)->findSitesByDistrict($district);
        return new \Symfony\Component\HttpFoundation\JsonResponse($sites);
    }

    public function getTAT() {
        $eidRepo = new \App\Repository\EIDTestRepository($this->getDoctrine());
        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        $tat1_ = $eidRepo->getTAT1($start, $end);
        $tat2_ = $eidRepo->getTAT2($start, $end);
        $tat3_ = $eidRepo->getTAT3($start, $end);
        $tat = [];
        if (count($tat1_) == 0) {
            $tat['tat1'][] = 0;
        }
        if (count($tat2_) == 0) {
            $tat['tat2'][] = 0;
        }
        if (count($tat3_) == 0) {
            $tat['tat3'][] = 0;
        }
        foreach ($tat1_ as $t) {
            $tat['tat1'][] = $t['tat1'];
        }
        foreach ($tat2_ as $t) {
            $tat['tat2'][] = $t['tat2'];
        }
        foreach ($tat3_ as $t) {
            $tat['tat3'][] = $t['tat3'];
        }
        $tat1_limit = [Util::ct($tat['tat1'][0]), end($tat['tat1'])];
        $tat2_limit = [Util::ct($tat['tat2'][0]), end($tat['tat2'])];
        $tat3_limit = [Util::ct($tat['tat3'][0]), end($tat['tat3'])];
        $t1 = Util::median($tat['tat1']);
        $t2 = Util::median($tat['tat2']);
        $t3 = Util::median($tat['tat3']);
        $r['tat'] = [$t1, $t2, $t3];
        $r['limits'] = [$tat1_limit, $tat2_limit, $tat3_limit];
        return $r;
    }

    /**
     * @Route("/summary_infos", name="app_summary_infos")
     */
    public function summaryInfos(Request $request, TranslatorInterface $translator) {
        $eidRepo = new \App\Repository\EIDTestRepository($this->getDoctrine());
        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        $total_tests = $eidRepo->getEIDTestSummary($start, $end);
        $total_patient = $eidRepo->getEIDTotalPatient($start, $end);
        $results = $eidRepo->getEIDPositivity($start, $end);
        $tats = $this->getTAT();
        $res['positif'] = 0;
        $res['negatif'] = 0;
        foreach ($results as $value) {
            if ($value['result'] == 'Positif') {
                $res['positif'] = $value['nb'];
            }
            if ($value['result'] == 'Négatif') {
                $res['negatif'] = $value['nb'];
            }
        }
        $pos = 0;
        if (array_sum($res) != 0) {
            $pos = number_format(intval($res['positif']) * 100 / array_sum($res), 1);
        }
        $data = [];
        //data for TAT
        $categories[] = $translator->trans('TAT (Jours)');
        $data[0]['data'][] = $tats['tat'][0];
        $data[0]['name'] = $translator->trans('Du prélèvement à la reception');
        $data[0]['type'] = 'bar';
        $data[1]['data'][] = $tats['tat'][1];
        $data[1]['name'] = $translator->trans('De la reception au traitement');
        $data[1]['type'] = 'bar';
        $data[2]['data'][] = $tats['tat'][2];
        $data[2]['name'] = $translator->trans('Du traitement à la validation');
        $data[2]['type'] = 'bar';
        return $this->render('home/summary_infos.html.twig', [
                    'tests' => $total_tests[0],
                    'patients' => $total_patient[0],
                    'pos' => $pos,
                    'series' => json_encode($data),
                    'categories' => json_encode($categories),
                    'limits' => $tats['limits'],
        ]);
    }

    /**
     * @Route("/date_filter", name="app_date_filter")
     */
    public function dateFilter(Request $request, TranslatorInterface $translator) {
        $locale = strtoupper($request->getLocale());
        $start = $request->request->get('startDate');
        $end = $request->request->get('endDate');
        $startDate_parts = explode(" ", $start, 2);
        $endDate_parts = explode(" ", $end, 2);
        $startDate = intval(date("Y") . '01');
        $endDate = intval(date("Ym"));
        if (is_array($startDate_parts) && count($startDate_parts) == 2) {
            $startDate = $startDate_parts[1] . array_search(Util::MONTHS_KEYS[$locale][trim($startDate_parts[0])], Util::MONTHS);
        }
        if (is_array($endDate_parts) && count($endDate_parts) == 2) {
            $endDate = $endDate_parts[1] . array_search(Util::MONTHS_KEYS[$locale][trim($endDate_parts[0])], Util::MONTHS);
        }
        //$this->get('session')->set('filterStartDate', $start);
        $this->get('session')->set('startDate', $startDate);
        //$this->get('session')->set('filterEndDate', $end);
        $this->get('session')->set('endDate', $endDate);
        return new \Symfony\Component\HttpFoundation\JsonResponse(['stat' => 1]);
    }

    /**
     * @Route("/reset_date_filter", name="app_reset_date_filter")
     */
    public function resetDateFilter(Request $request) {
        $this->get('session')->remove('startDate');
        $this->get('session')->remove('endDate');
        return new \Symfony\Component\HttpFoundation\JsonResponse(['stat' => 1]);
    }

    /**
     * @Route("/tests_trends/{which_pcr}", name="home_tests_trends",requirements={"which_pcr"="-?\d+"})
     */
    public function testTrends(Request $request, TranslatorInterface $translator, int $which_pcr = 0) {
        $eidRepo = new \App\Repository\EIDTestRepository($this->getDoctrine());
        $start = $this->get('session')->get("startDate");
        $end = $this->get('session')->get("endDate");
        $start_year = intval(substr($start, 0, 4));
        $end_year = intval(substr($end, 0, 4));
        if ($end_year > intval(date("Y"))) {
            $end_year = intval(date("Y"));
        }
        $end_month = intval(substr($end, 4, 2));
        if (null === $start) {
            $start_year = intval(date("Y")) - 1;
            $start = intval($start_year . '01');
        }
        if (null === $end) {
            $end = intval(date("Ym"));
            $end_year = intval(date("Y"));
            $end_month = intval(date("m"));
        }
        $start2 = intval(substr($start, 0, 4) . '01');
        $outcomes = $eidRepo->getEIDTestingTrends($which_pcr, $start2, $end);

        $d = [];
        $periodes = [];

        $i = 0;
        for ($j = $start_year; $j <= $end_year; $j++) {
            for ($k = 1; $k <= 12; $k++) {
                $periodes[$i]['year'] = $j;
                $periodes[$i]['month'] = $k;
                if ($j == $end_year && $k == $end_month) {
                    break;
                }
                $i++;
            }
        }
//prepare categories
        $categories = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        foreach ($periodes as $v) {
            $categories[] = $translator->trans($months[$v['month'] - 1]) . '-' . $v['year'];
        }
//prepare series data
        $d[0]['name'] = $translator->trans('Positive');
        $d[0]['type'] = 'column';
        $d[0]['color'] = $this->getParameter('pos_color');
        $d[0]['yAxis'] = 1;
        $d[1]['name'] = $translator->trans('Negative');
        $d[1]['type'] = 'column';
        $d[1]['color'] = $this->getParameter('neg_color');
        $d[1]['yAxis'] = 1;
        $d[2]['name'] = $translator->trans('Invalide');
        $d[2]['type'] = 'column';
        $d[2]['color'] = $this->getParameter('inv_color');
        $d[2]['yAxis'] = 1;
        $d[3]['name'] = $translator->trans('Positivité');
        $d[3]['type'] = 'spline';
        $d[3]['color'] = $this->getParameter('pos_color2');
        $d[3]['tooltip']['valueSuffix'] = " %";
        foreach ($periodes as $p) {
            foreach ($outcomes as $entry) {
                if ($entry['year'] == $p['year'] && $entry['month'] == $p['month']) {
                    $d[0]['data'][] = intval($entry['positif']);
                    $d[1]['data'][] = intval($entry['negatif']);
                    $d[2]['data'][] = intval($entry['invalide']);
                    if (intval($entry['negatif'] + intval($entry['positif']) + intval($entry['invalide'])) == 0) {
                        $d[3]['data'][] = 0;
                    } else {
                        $d[3]['data'][] = floatval(number_format((intval($entry['positif']) / (intval($entry['negatif'] + intval($entry['positif']) + intval($entry['invalide'])))) * 100, 2));
                    }
                    continue(2);
                }
            }
            $d[0]['data'][] = 0;
            $d[1]['data'][] = 0;
            $d[2]['data'][] = 0;
            $d[3]['data'][] = 0;
        }

        return $this->render('home/test_trends.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
                    'start' => $translator->trans(Util::MONTHS[substr($start, 4, 2)]) . ' ' . substr($start, 0, 4),
                    'end' => $translator->trans(Util::MONTHS[substr($end, 4, 2)]) . ' ' . substr($end, 0, 4),
        ]);
    }

    private function getOutcomesDetails($start, $end) {
        $eidRepo = new \App\Repository\EIDTestRepository($this->getDoctrine());
        $outcomes = $eidRepo->getEidOutcomesDetails($start, $end);
        if (count($outcomes) == 0) {
            $outcomes = [
                0 => [
                    'positif' => 0,
                    'negatif' => 0,
                    'invalide' => 0,
                ],
                1 => [
                    'positif' => 0,
                    'negatif' => 0,
                    'invalide' => 0,
                ],
                2 => [
                    'positif' => 0,
                    'negatif' => 0,
                    'invalide' => 0,
                ],
            ];
        }
        return $outcomes;
    }

    /**
     * @Route("/eid_outcomes", name="home_eid_outcomes")
     */
    public function eidOutcomes(Request $request, TranslatorInterface $translator) {
        $eidRepo = new \App\Repository\EIDTestRepository($this->getDoctrine());
        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();

        $details = $this->getOutcomesDetails($start, $end);
        $which_pcr = $this->getParameter('default_pcr'); // PCR 1 only
        $outcomes = $eidRepo->getEidOutcomes($which_pcr, $start, $end);

//prepare series data
        $d = [];
        $d[0]['name'] = $translator->trans('Positive');
        $d[1]['name'] = $translator->trans('Negative');
        $d[2]['name'] = $translator->trans('Invalide');
        $d[0]['y'] = 0;
        $d[0]['color'] = $this->getParameter('pos_color');
        $d[1]['y'] = 0;
        $d[1]['color'] = $this->getParameter('neg_color');
        $d[2]['y'] = 0;
        $d[2]['color'] = $this->getParameter('inv_color');
        foreach ($outcomes as $entry) {
            $d[0]['y'] += $entry['positif'];
            $d[1]['y'] += $entry['negatif'];
            $d[2]['y'] += $entry['invalide'];
        }

        return $this->render('home/test_outcomes.html.twig', [
                    'series' => json_encode($d),
                    'details' => $details,
        ]);
    }

    /**
     * @Route("/eid_outcomes_age", name="home_eid_outcomes_age")
     */
    public function eidOutcomesAge(Request $request, TranslatorInterface $translator) {
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

        $eidRepo = new \App\Repository\EIDTestRepository($this->getDoctrine());

        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        $which_pcr = $this->getParameter('default_pcr'); // PCR 1 only
        $outcomes = $eidRepo->getEidOutcomes($which_pcr, $start, $end);

        $d = [];

//prepare series data
        $d[0]['name'] = $translator->trans('Positive');
        $d[0]['type'] = 'column';
        $d[0]['color'] = $this->getParameter('pos_color');
        $d[0]['yAxis'] = 1;
        $d[1]['name'] = $translator->trans('Negative');
        $d[1]['type'] = 'column';
        $d[1]['color'] = $this->getParameter('neg_color');
        $d[1]['yAxis'] = 1;
        $d[2]['name'] = $translator->trans('Invalide');
        $d[2]['type'] = 'column';
        $d[2]['color'] = $this->getParameter('inv_color');
        $d[2]['yAxis'] = 1;
        $d[3]['name'] = $translator->trans('Positivité');
        $d[3]['type'] = 'spline';
        $d[3]['color'] = $this->getParameter('pos_color2');
        $d[3]['tooltip']['valueSuffix'] = " %";
        $u = 0;
        foreach ($ages as $age) {
            $d[0]['data'][$u] = 0;
            $d[1]['data'][$u] = 0;
            $d[2]['data'][$u] = 0;
            $d[3]['data'][$u] = 0;
            foreach ($outcomes as $entry) {
                if ($entry['age_month'] >= $age['limits'][0] && $entry['age_month'] < $age['limits'][1]) {
                    $d[0]['data'][$u] += intval($entry['positif']);
                    $d[1]['data'][$u] += intval($entry['negatif']);
                    $d[2]['data'][$u] += intval($entry['invalide']);
                }
            }
            if ($d[0]['data'][$u] + $d[1]['data'][$u] + $d[2]['data'][$u] == 0) {
                $d[3]['data'][$u] += 0;
            } else {
                $d[3]['data'][$u] += floatval(number_format(($d[0]['data'][$u] / ($d[0]['data'][$u] + $d[1]['data'][$u] + $d[2]['data'][$u])) * 100, 2));
            }
            $u++;
        }

//for rows that not contains right age values -- others
        $nb_age = count($ages);
        $d[0]['data'][$nb_age] = 0;
        $d[1]['data'][$nb_age] = 0;
        $d[2]['data'][$nb_age] = 0;
        $d[3]['data'][$nb_age] = 0;
        foreach ($outcomes as $entry) {
            if ($entry['age_month'] < 0 || !is_numeric($entry['age_month'])) {
                $d[0]['data'][$nb_age] += intval($entry['positif']);
                $d[1]['data'][$nb_age] += intval($entry['negatif']);
                $d[2]['data'][$nb_age] += intval($entry['invalide']);
            }
        }
        if ($d[0]['data'][$nb_age] + $d[1]['data'][$nb_age] + $d[2]['data'][$nb_age] == 0) {
            $d[3]['data'][$nb_age] += 0;
        } else {
            $d[3]['data'][$nb_age] += floatval(number_format(($d[0]['data'][$nb_age] / ($d[0]['data'][$nb_age] + $d[1]['data'][$nb_age] + $d[2]['data'][$nb_age])) * 100, 2));
        }
        return $this->render('home/test_outcomes_age.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
        ]);
    }

    /**
     * @Route("/eid_outcomes_mother_status", name="home_eid_outcomes_mother_status")
     */
    public function eidOutcomesMotherStatus(Request $request, TranslatorInterface $translator) {
        $agesCategories = $this->getDoctrine()->getRepository(\App\Entity\EIDDictionary::class)->getMotherHIVStatus();
        $status = [];
        $categories = [];
        $k = 0;
        foreach ($agesCategories as $value) {
            $status[$k]['name'] = $value['name'];
            $categories[] = $translator->trans($value['name']);
            $k++;
        }
        $categories[] = $translator->trans('Aucune donnée');

        $eidRepo = new \App\Repository\EIDTestRepository($this->getDoctrine());

        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        $which_pcr = $this->getParameter('default_pcr'); // PCR 1 only
        $outcomes = $eidRepo->getEidOutcomesByMotherStatus($which_pcr, $start, $end);

        $d = [];

//prepare series data
        $d[0]['name'] = $translator->trans('Positive');
        $d[0]['type'] = 'column';
        $d[0]['color'] = $this->getParameter('pos_color');
        $d[0]['yAxis'] = 1;
        $d[1]['name'] = $translator->trans('Negative');
        $d[1]['type'] = 'column';
        $d[1]['color'] = $this->getParameter('neg_color');
        $d[1]['yAxis'] = 1;
        $d[2]['name'] = $translator->trans('Invalide');
        $d[2]['type'] = 'column';
        $d[2]['color'] = $this->getParameter('inv_color');
        $d[2]['yAxis'] = 1;
        $d[3]['name'] = $translator->trans('Positivité');
        $d[3]['type'] = 'spline';
        $d[3]['color'] = $this->getParameter('pos_color2');
        $d[3]['tooltip']['valueSuffix'] = " %";
        $u = 0;
        foreach ($status as $stat) {
            $d[0]['data'][$u] = 0;
            $d[1]['data'][$u] = 0;
            $d[2]['data'][$u] = 0;
            $d[3]['data'][$u] = 0;
            foreach ($outcomes as $entry) {
                if ($entry['hiv_status'] == $stat['name']) {
                    $d[0]['data'][$u] += intval($entry['positif']);
                    $d[1]['data'][$u] += intval($entry['negatif']);
                    $d[2]['data'][$u] += intval($entry['invalide']);
                }
            }
            if ($d[0]['data'][$u] + $d[1]['data'][$u] + $d[2]['data'][$u] == 0) {
                $d[3]['data'][$u] += 0;
            } else {
                $d[3]['data'][$u] += floatval(number_format(($d[0]['data'][$u] / ($d[0]['data'][$u] + $d[1]['data'][$u] + $d[2]['data'][$u])) * 100, 2));
            }
            $u++;
        }

//for rows that not contains right age values -- others
        $d[0]['data'][$u] = 0;
        $d[1]['data'][$u] = 0;
        $d[2]['data'][$u] = 0;
        $d[3]['data'][$u] = 0;
        foreach ($outcomes as $entry) {
            if (is_null($entry['hiv_status'])) {
                $d[0]['data'][$u] += intval($entry['positif']);
                $d[1]['data'][$u] += intval($entry['negatif']);
                $d[2]['data'][$u] += intval($entry['invalide']);
            }
        }
        if ($d[0]['data'][$u] + $d[1]['data'][$u] + $d[2]['data'][$u] == 0) {
            $d[3]['data'][$u] += 0;
        } else {
            $d[3]['data'][$u] += floatval(number_format(($d[0]['data'][$u] / ($d[0]['data'][$u] + $d[1]['data'][$u] + $d[2]['data'][$u])) * 100, 2));
        }
        return $this->render('home/test_outcomes_mother_status.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
        ]);
    }

    /**
     * @Route("/eid_outcomes_clinic", name="home_eid_outcomes_clinic")
     */
    public function eidOutcomesByClinicType(Request $request, TranslatorInterface $translator) {
        $typeOfClinics = $this->getDoctrine()->getRepository(\App\Entity\EIDDictionary::class)->getTypeOfClinic();
        $types = [];
        $categories = [];
        $k = 0;
        foreach ($typeOfClinics as $value) {
            $types[$k] = $value['entry_name'];
            $categories[$k] = $translator->trans($value['entry_name']);
            $k++;
        }
        $categories[] = $translator->trans('Aucune donnée');

        $eidRepo = new \App\Repository\EIDTestRepository($this->getDoctrine());

        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        $which_pcr = $this->getParameter('default_pcr'); // PCR  only
        $outcomes = $eidRepo->getEidOutcomesByClinicType($which_pcr, $start, $end);

        $d = [];
//prepare series data
        $d[0]['name'] = $translator->trans('Positive');
        $d[0]['type'] = 'column';
        $d[0]['color'] = $this->getParameter('pos_color');
        $d[0]['yAxis'] = 1;
        $d[1]['name'] = $translator->trans('Negative');
        $d[1]['type'] = 'column';
        $d[1]['color'] = $this->getParameter('neg_color');
        $d[1]['yAxis'] = 1;
        $d[2]['name'] = $translator->trans('Invalide');
        $d[2]['type'] = 'column';
        $d[2]['color'] = $this->getParameter('inv_color');
        $d[2]['yAxis'] = 1;
        $d[3]['name'] = $translator->trans('Positivité');
        $d[3]['type'] = 'spline';
        $d[3]['color'] = $this->getParameter('pos_color2');
        $d[3]['tooltip']['valueSuffix'] = " %";
        $u = 0;
        foreach ($types as $type) {
            $d[0]['data'][$u] = 0;
            $d[1]['data'][$u] = 0;
            $d[2]['data'][$u] = 0;
            $d[3]['data'][$u] = 0;
            foreach ($outcomes as $entry) {
                if ($entry['clinic'] == $type) {
                    $d[0]['data'][$u] += intval($entry['positif']);
                    $d[1]['data'][$u] += intval($entry['negatif']);
                    $d[2]['data'][$u] += intval($entry['invalide']);
                }
            }
            if ($d[0]['data'][$u] + $d[1]['data'][$u] + $d[2]['data'][$u] == 0) {
                $d[3]['data'][$u] += 0;
            } else {
                $d[3]['data'][$u] += floatval(number_format(($d[0]['data'][$u] / ($d[0]['data'][$u] + $d[1]['data'][$u] + $d[2]['data'][$u])) * 100, 2));
            }
            $u++;
        }
//for rows that not contains right age values -- others
        $d[0]['data'][$u] = 0;
        $d[1]['data'][$u] = 0;
        $d[2]['data'][$u] = 0;
        $d[3]['data'][$u] = 0;
        foreach ($outcomes as $entry) {
            if (is_null($entry['clinic'])) {
                $d[0]['data'][$u] += intval($entry['positif']);
                $d[1]['data'][$u] += intval($entry['negatif']);
                $d[2]['data'][$u] += intval($entry['invalide']);
            }
        }
        if ($d[0]['data'][$u] + $d[1]['data'][$u] + $d[2]['data'][$u] == 0) {
            $d[3]['data'][$u] += 0;
        } else {
            $d[3]['data'][$u] += floatval(number_format(($d[0]['data'][$u] / ($d[0]['data'][$u] + $d[1]['data'][$u] + $d[2]['data'][$u])) * 100, 2));
        }
        return $this->render('home/test_outcomes_clinic.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
        ]);
    }

    /**
     * @Route("/eid_outcomes_mother_regimen", name="home_eid_outcomes_mother_regimen")
     */
    public function eidOutcomesByMotherRegimen(Request $request, TranslatorInterface $translator) {
        $typeOfClinics = $this->getDoctrine()->getRepository(\App\Entity\EIDDictionary::class)->getMotherRegimen();
        $types = [];
        $categories = [];
        $k = 0;
        foreach ($typeOfClinics as $value) {
            $types[$k] = $value['entry_name'];
            $categories[$k] = $translator->trans($value['entry_name']);
            $k++;
        }
        $categories[] = $translator->trans('Aucune donnée');

        $eidRepo = new \App\Repository\EIDTestRepository($this->getDoctrine());

        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        $which_pcr = $this->getParameter('default_pcr'); // PCR 1 only
        $outcomes = $eidRepo->getEidOutcomesByMotherRegimen($which_pcr, $start, $end);

        $d = [];

//prepare series data
        $d[0]['name'] = $translator->trans('Positive');
        $d[0]['type'] = 'column';
        $d[0]['color'] = $this->getParameter('pos_color');
        $d[0]['yAxis'] = 1;
        $d[1]['name'] = $translator->trans('Negative');
        $d[1]['type'] = 'column';
        $d[1]['color'] = $this->getParameter('neg_color');
        $d[1]['yAxis'] = 1;
        $d[2]['name'] = $translator->trans('Invalide');
        $d[2]['type'] = 'column';
        $d[2]['color'] = $this->getParameter('inv_color');
        $d[2]['yAxis'] = 1;
        $d[3]['name'] = $translator->trans('Positivité');
        $d[3]['type'] = 'spline';
        $d[3]['color'] = $this->getParameter('pos_color2');
        $d[3]['tooltip']['valueSuffix'] = " %";
        $u = 0;
        foreach ($types as $type) {
            $d[0]['data'][$u] = 0;
            $d[1]['data'][$u] = 0;
            $d[2]['data'][$u] = 0;
            $d[3]['data'][$u] = 0;
            foreach ($outcomes as $entry) {
                if ($entry['mother_regimen'] == $type) {
                    $d[0]['data'][$u] += intval($entry['positif']);
                    $d[1]['data'][$u] += intval($entry['negatif']);
                    $d[2]['data'][$u] += intval($entry['invalide']);
                }
            }
            if ($d[0]['data'][$u] + $d[1]['data'][$u] + $d[2]['data'][$u] == 0) {
                $d[3]['data'][$u] += 0;
            } else {
                $d[3]['data'][$u] += floatval(number_format(($d[0]['data'][$u] / ($d[0]['data'][$u] + $d[1]['data'][$u] + $d[2]['data'][$u])) * 100, 2));
            }
            $u++;
        }

//for rows that not contains right age values -- others
        $d[0]['data'][$u] = 0;
        $d[1]['data'][$u] = 0;
        $d[2]['data'][$u] = 0;
        $d[3]['data'][$u] = 0;
        foreach ($outcomes as $entry) {
            if (is_null($entry['mother_regimen'])) {
                $d[0]['data'][$u] += intval($entry['positif']);
                $d[1]['data'][$u] += intval($entry['negatif']);
                $d[2]['data'][$u] += intval($entry['invalide']);
            }
        }
        if ($d[0]['data'][$u] + $d[1]['data'][$u] + $d[2]['data'][$u] == 0) {
            $d[3]['data'][$u] += 0;
        } else {
            $d[3]['data'][$u] += floatval(number_format(($d[0]['data'][$u] / ($d[0]['data'][$u] + $d[1]['data'][$u] + $d[2]['data'][$u])) * 100, 2));
        }
        return $this->render('home/test_outcomes_mother_regimen.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
        ]);
    }

    /**
     * @Route("/eid_outcomes_infant_arv", name="home_eid_outcomes_infant_arv")
     */
    public function eidOutcomesByInfantARV(Request $request, TranslatorInterface $translator) {
        $typeOfClinics = $this->getDoctrine()->getRepository(\App\Entity\EIDDictionary::class)->getInfantARV();
        $types = [];
        $categories = [];
        $k = 0;
        foreach ($typeOfClinics as $value) {
            $types[$k] = $value['entry_name'];
            $categories[$k] = $translator->trans($value['entry_name']);
            $k++;
        }
        $categories[] = $translator->trans('Aucune donnée');

        $eidRepo = new \App\Repository\EIDTestRepository($this->getDoctrine());

        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        $which_pcr = $this->getParameter('default_pcr'); // PCR 1 only
        $outcomes = $eidRepo->getEidOutcomesByInfantARV($which_pcr, $start, $end);

        $d = [];

//prepare series data
        $d[0]['name'] = $translator->trans('Positive');
        $d[0]['type'] = 'column';
        $d[0]['color'] = $this->getParameter('pos_color');
        $d[0]['yAxis'] = 1;
        $d[1]['name'] = $translator->trans('Negative');
        $d[1]['type'] = 'column';
        $d[1]['color'] = $this->getParameter('neg_color');
        $d[1]['yAxis'] = 1;
        $d[2]['name'] = $translator->trans('Invalide');
        $d[2]['type'] = 'column';
        $d[2]['color'] = $this->getParameter('inv_color');
        $d[2]['yAxis'] = 1;
        $d[3]['name'] = $translator->trans('Positivité');
        $d[3]['type'] = 'spline';
        $d[3]['color'] = $this->getParameter('pos_color2');
        $d[3]['tooltip']['valueSuffix'] = " %";
        $u = 0;
        foreach ($types as $type) {
            $d[0]['data'][$u] = 0;
            $d[1]['data'][$u] = 0;
            $d[2]['data'][$u] = 0;
            $d[3]['data'][$u] = 0;
            foreach ($outcomes as $entry) {
                if ($entry['infant_arv'] == $type) {
                    $d[0]['data'][$u] += intval($entry['positif']);
                    $d[1]['data'][$u] += intval($entry['negatif']);
                    $d[2]['data'][$u] += intval($entry['invalide']);
                }
            }
            if ($d[0]['data'][$u] + $d[1]['data'][$u] + $d[2]['data'][$u] == 0) {
                $d[3]['data'][$u] += 0;
            } else {
                $d[3]['data'][$u] += floatval(number_format(($d[0]['data'][$u] / ($d[0]['data'][$u] + $d[1]['data'][$u] + $d[2]['data'][$u])) * 100, 2));
            }
            $u++;
        }

//for rows that not contains right age values -- others
        $d[0]['data'][$u] = 0;
        $d[1]['data'][$u] = 0;
        $d[2]['data'][$u] = 0;
        $d[3]['data'][$u] = 0;
        foreach ($outcomes as $entry) {
            if (is_null($entry['infant_arv'])) {
                $d[0]['data'][$u] += intval($entry['positif']);
                $d[1]['data'][$u] += intval($entry['negatif']);
                $d[2]['data'][$u] += intval($entry['negatif']);
            }
        }
        if ($d[0]['data'][$u] + $d[1]['data'][$u] + $d[2]['data'][$u] == 0) {
            $d[3]['data'][$u] += 0;
        } else {
            $d[3]['data'][$u] += floatval(number_format(($d[0]['data'][$u] / ($d[0]['data'][$u] + $d[1]['data'][$u] + $d[2]['data'][$u])) * 100, 2));
        }
        return $this->render('home/test_outcomes_infant_arv.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
        ]);
    }

    /**
     * @Route("/eid_outcomes_infant_arv", name="home_eid_outcomes_infant_arv")
     */
    public function eidOutcomesByRegion(Request $request, TranslatorInterface $translator) {
        $regions = $this->getDoctrine()->getRepository(\App\Entity\Region::class)->findRegions();
        $types = [];
        $categories = [];
        $k = 0;
        foreach ($regions as $value) {
            $types[$k] = $value['name'];
            $categories[$k] = $value['name'];
            $k++;
        }
        $categories[] = $translator->trans('Aucune donnée');

        $eidRepo = new \App\Repository\EIDTestRepository($this->getDoctrine());

        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        $which_pcr = $this->getParameter('default_pcr'); // PCR 1 only
        $outcomes = $eidRepo->getEidOutcomesByRegion($which_pcr, $start, $end);

        $d = [];

//prepare series data
        $d[0]['name'] = $translator->trans('Positive');
        $d[0]['type'] = 'column';
        $d[0]['color'] = $this->getParameter('pos_color');
        $d[0]['yAxis'] = 1;
        $d[1]['name'] = $translator->trans('Negative');
        $d[1]['type'] = 'column';
        $d[1]['color'] = $this->getParameter('neg_color');
        $d[1]['yAxis'] = 1;
        $d[2]['name'] = $translator->trans('Invalide');
        $d[2]['type'] = 'column';
        $d[2]['color'] = $this->getParameter('inv_color');
        $d[2]['yAxis'] = 1;
        $d[3]['name'] = $translator->trans('Positivité');
        $d[3]['type'] = 'spline';
        $d[3]['color'] = $this->getParameter('pos_color2');
        $d[3]['tooltip']['valueSuffix'] = " %";
        $u = 0;
        foreach ($types as $type) {
            $d[0]['data'][$u] = 0;
            $d[1]['data'][$u] = 0;
            $d[2]['data'][$u] = 0;
            $d[3]['data'][$u] = 0;
            foreach ($outcomes as $entry) {
                if ($entry['region'] == $type) {
                    $d[0]['data'][$u] += intval($entry['positif']);
                    $d[1]['data'][$u] += intval($entry['negatif']);
                    $d[2]['data'][$u] += intval($entry['invalide']);
                }
            }
            $total = $d[0]['data'][$u] + $d[1]['data'][$u] + $d[2]['data'][$u];
            if ($total == 0) {
                $d[3]['data'][$u] += 0;
            } else {
                $d[3]['data'][$u] += floatval(number_format(($d[0]['data'][$u] / ($total)) * 100, 2));
            }
            $u++;
        }

//for rows that not contains right age values -- others
        $d[0]['data'][$u] = 0;
        $d[1]['data'][$u] = 0;
        $d[2]['data'][$u] = 0;
        $d[3]['data'][$u] = 0;
        foreach ($outcomes as $entry) {
            if (is_null($entry['region'])) {
                $d[0]['data'][$u] += intval($entry['positif']);
                $d[1]['data'][$u] += intval($entry['negatif']);
                $d[2]['data'][$u] += intval($entry['invalide']);
            }
        }
        if ($d[0]['data'][$u] + $d[1]['data'][$u] + $d[2]['data'][$u] == 0) {
            $d[3]['data'][$u] += 0;
        } else {
            $d[3]['data'][$u] += floatval(number_format(($d[0]['data'][$u] / ($d[0]['data'][$u] + $d[1]['data'][$u] + $d[2]['data'][$u])) * 100, 2));
        }
        return $this->render('home/test_outcomes_region.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
        ]);
    }

}
