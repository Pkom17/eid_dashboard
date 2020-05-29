<?php

#src/Controller/SiteController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Utilities\Util;

/**
 * Description of OrganizationController
 *
 * @author PKom
 */
class SiteController extends OrganizationController {

    /**
     * @Route("/organization/site/sites_stat/{district_id?0}", name="app_sites_stats",requirements={"district_id"="\d+"})
     */
    public function sitesStat(int $district_id = 0) {
        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        $rows = $this->getDoctrine()->getRepository(\App\Entity\Site::class)->getEidSitesStats($start, $end, $district_id);
        return $this->render('organization/site/tab_stats_site.html.twig', [
                    'stats' => $rows,
        ]);
    }

    /**
     * @Route("/organization/site/outcomes/{district_id?0}", name="app_org_sites_outcomes",requirements={"district_id"="\d+"})
     */
    public function eidOutcomesBySites(TranslatorInterface $translator, int $district_id = 0) {
        $sites = $this->getDoctrine()->getRepository(\App\Entity\Site::class)->findSites();
        $types = [];
        $categories = [];
        $k = 0;
        foreach ($sites as $value) {
            $types[$k] = $value['name'];
            $k++;
        }

        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        $outcomes = $this->getDoctrine()->getRepository(\App\Entity\Site::class)->getEidOutcomesBySite($start, $end, $district_id);

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
        $d[0]['data'][$u] = 0;
        $d[1]['data'][$u] = 0;
        $d[2]['data'][$u] = 0;
        $d[3]['data'][$u] = 0;
        $col_limit = $this->getParameter('graph_column_limit');
        foreach ($outcomes as $entry) {
            if (is_null($entry['site']) || $entry['site'] == 'null' || $entry['site'] == '') {
                $categories[$u] = $translator->trans('Aucune donnée');
            } else {
                $categories[$u] = $entry['site'];
            }
            $d[0]['data'][$u] = intval($entry['positif']);
            $d[1]['data'][$u] = intval($entry['negatif']);
            $d[2]['data'][$u] = intval($entry['invalide']);
            if (intval($entry['total']) == 0) {
                $d[3]['data'][$u] = 0;
            } else {
                $d[3]['data'][$u] = floatval(number_format(($d[0]['data'][$u] / (intval($entry['total']))) * 100, 2));
            }
            if ($u == $col_limit) {
                break;
            }
            $u++;
        }
        return $this->render('organization/site/sites_outcomes.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
        ]);
    }

    /**
     * @Route("/organization/site/overview", name="app_sites_overview")
     */
    public function sitesOverview(TranslatorInterface $translator) {
        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        return $this->render('organization/site/sites_overview.html.twig', [
                    'start' => $translator->trans(Util::MONTHS[substr($start, 4, 2)]) . ' ' . substr($start, 0, 4),
                    'end' => $translator->trans(Util::MONTHS[substr($end, 4, 2)]) . ' ' . substr($end, 0, 4),
        ]);
    }

    /**
     * @Route("/organization/site/site_details/{site_id?0}", name="app_site_details",requirements={"site_id"="\d+"})
     */
    public function siteDetails(TranslatorInterface $translator, int $site_id = 0) {
        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        $region = $this->getDoctrine()->getRepository(\App\Entity\Site::class)->findOneBy(['id' => $site_id]);
        return $this->render('organization/site/site_details.html.twig', [
                    'site' => $region,
                    'site_id' => $site_id,
                    'start' => $translator->trans(Util::MONTHS[substr($start, 4, 2)]) . ' ' . substr($start, 0, 4),
                    'end' => $translator->trans(Util::MONTHS[substr($end, 4, 2)]) . ' ' . substr($end, 0, 4),
        ]);
    }

    /**
     * @Route("/organization/site/trends/{site_id?0}", name="app_org_site_trends",requirements={"site_id"="\d+"})
     */
    public function testsTrendsSites(TranslatorInterface $translator, int $site_id = 0) {
        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        $d = [];
        $periodes = [];
        $i = 0;
        $start_year = intval(substr($start, 0, 4));
        $end_year = intval(substr($end, 0, 4));
        if ($end_year > intval(date("Y"))) {
            $end_year = intval(date("Y"));
        }
        $end_month = intval(substr($end, 4, 2));
        $start2 = intval(substr($start, 0, 4) . '01');
        $outcomes = $this->getDoctrine()->getRepository(\App\Entity\Site::class)->getTestsTrendsSite($site_id, $start2, $end);
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

        return $this->render('organization/site/test_trends.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
                    'start' => $translator->trans(Util::MONTHS[substr($start, 4, 2)]) . ' ' . substr($start, 0, 4),
                    'end' => $translator->trans(Util::MONTHS[substr($end, 4, 2)]) . ' ' . substr($end, 0, 4),
        ]);
    }

    protected function getOutcomesByPCR(?TranslatorInterface $translator, $site_id, $start, $end) {
        $outcomes = $this->getDoctrine()->getRepository(\App\Entity\Site::class)->getSiteOutcomesPCR($site_id, $start, $end);
        $d = [];
        $d[0]['name'] = $translator->trans('PCR 1');
        $d[1]['name'] = $translator->trans('PCR 2');
        $d[2]['name'] = $translator->trans('Non défini');
        $d[0]['y'] = 0;
        $d[0]['color'] = '#5af';
        $d[1]['y'] = 0;
        $d[1]['color'] = '#24a';
        $d[2]['y'] = 0;
        $d[2]['color'] = '#222';
        foreach ($outcomes as $entry) {
            $d[0]['y'] += $entry['pcr1'];
            $d[1]['y'] += $entry['pcr2'];
            $d[2]['y'] += $entry['pcr_undefined'];
        }
        return json_encode($d);
    }

    /**
     * @Route("/organization/site/site_outcomes/{site_id?0}", name="app_org_site_outcomes",requirements={"site_id"="\d+"})
     */
    public function siteOutcomes(TranslatorInterface $translator, int $site_id) {
        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        // details informations 
        $details2 = [];
        $details = $this->getDoctrine()->getRepository(\App\Entity\Site::class)->getSiteOutcomesDetails($site_id, $start, $end);
        if (count($details) == 0) {
            $details2 = [
                0 => ['positif' => 0, 'negatif' => 0,],
                1 => ['positif' => 0, 'negatif' => 0,],
                2 => ['positif' => 0, 'negatif' => 0,],
            ];
        } else {
            foreach ($details as $detail) {
                $temp[$detail['which_pcr']]['positif'] = $detail['positif'];
                $temp[$detail['which_pcr']]['negatif'] = $detail['negatif'];
            }

            for ($a = 0; $a < 3; $a++) {
                if (isset($temp[$a])) {
                    $details2[$a] = $temp[$a];
                } else {
                    $details2[$a] = ['positif' => 0, 'negatif' => 0,];
                }
            }
        }
        // end details
        $outcomes = $this->getDoctrine()->getRepository(\App\Entity\Site::class)->getSiteOutcomes($site_id, $start, $end);

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
//        echo '<pre>';
//        print_r($details2);
//        die();
        return $this->render('organization/site/test_outcomes.html.twig', [
                    'series_1' => json_encode($d),
                    'series_2' => $this->getOutcomesByPCR($translator, $site_id, $start, $end),
                    'details' => $details2,
        ]);
    }

    /**
     * @Route("/organization/site/pcr2_reason/{site_id?0}", name="app_org_site_pcr2_reason",requirements={"site_id"="\d+"})
     */
    public function siteOutcomesByPCR2Reason(TranslatorInterface $translator, int $site_id) {
        $pcr2_reasons = $this->getDoctrine()->getRepository(\App\Entity\EIDDictionary::class)->getPCR2Reasons();
        $reasons = [];
        $categories = [];
        $k = 0;
        foreach ($pcr2_reasons as $value) {
            $reasons[$k] = $value['entry_name'];
            $k++;
        }
        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        $outcomes = $this->getDoctrine()->getRepository(\App\Entity\Site::class)->getSiteOutcomesByPCR2Reason($site_id, $start, $end);

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
        $d[0]['data'][$u] = 0;
        $d[1]['data'][$u] = 0;
        $d[2]['data'][$u] = 0;
        $d[3]['data'][$u] = 0;
        foreach ($outcomes as $entry) {
            if (is_null($entry['pcr2_reason']) || $entry['pcr2_reason'] == 'null' || $entry['pcr2_reason'] == '') {
                $categories[$u] = $translator->trans('Aucune donnée');
            } else {
                $categories[$u] = $entry['pcr2_reason'];
            }
            $d[0]['data'][$u] = intval($entry['positif']);
            $d[1]['data'][$u] = intval($entry['negatif']);
            $d[2]['data'][$u] = intval($entry['invalide']);
            if (intval($entry['total']) == 0) {
                $d[3]['data'][$u] = 0;
            } else {
                $d[3]['data'][$u] = floatval(number_format(($d[0]['data'][$u] / (intval($entry['total']))) * 100, 2));
            }
            $u++;
        }
        return $this->render('organization/site/test_outcomes_pcr2_reason.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
        ]);
    }

    /**
     * @Route("/organization/site/outcomes_age/{site_id?0}", name="app_org_site_outcomes_age",requirements={"site_id"="\d+"})
     */
    public function siteOutcomesByAge(TranslatorInterface $translator, int $site_id) {
        $ages = $this->getAgeInfos($translator)['ages'];
        $categories = $this->getAgeInfos($translator)['age_categories'];
        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        $outcomes = $this->getDoctrine()->getRepository(\App\Entity\Site::class)->getSiteOutcomesAge($site_id, $start, $end);

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
        return $this->render('organization/site/site_outcomes_age.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
        ]);
    }

    /**
     * @Route("/organization/site/clinic_type/{site_id?0}", name="app_eid_site_outcomes_clinic_type",requirements={"site_id"="\d+"})
     */
    public function eidOutcomesByClinicType(TranslatorInterface $translator, int $site_id) {
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
        $outcomes = $this->getDoctrine()->getRepository(\App\Entity\Site::class)->getEidOutcomesByClinicType($site_id, $this->getStartDateForFilter(), $this->getEndDateForFilter());

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
        return $this->render('organization/site/test_outcomes_clinic.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
        ]);
    }

    /**
     * @Route("/organization/site/mother_status/{site_id?0}", name="app_eid_site_outcomes_mother_status",requirements={"site_id"="\d+"})
     */
    public function eidOutcomesMotherStatus(TranslatorInterface $translator, int $site_id) {
        $motherStatus = $this->getDoctrine()->getRepository(\App\Entity\EIDDictionary::class)->getMotherHIVStatus();
        $types = [];
        $categories = [];
        $k = 0;
        foreach ($motherStatus as $value) {
            $types[$k] = $value['entry_name'];
            $categories[$k] = $translator->trans($value['entry_name']);
            $k++;
        }
        $categories[] = $translator->trans('Aucune donnée');
        $outcomes = $this->getDoctrine()->getRepository(\App\Entity\Site::class)->getEidOutcomesByMotherStatus($site_id, $this->getStartDateForFilter(), $this->getEndDateForFilter());

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
                if ($entry['hiv_status'] == $type) {
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
        return $this->render('organization/site/test_outcomes_mother_status.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
        ]);
    }

    /**
     * @Route("/organization/site/mother_regimen/{site_id?0}", name="app_eid_site_outcomes_monther_regimen",requirements={"site_id"="\d+"})
     */
    public function eidOutcomesByMotherRegimen(TranslatorInterface $translator, int $site_id) {
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
        $outcomes = $this->getDoctrine()->getRepository(\App\Entity\Site::class)->getEidOutcomesByMotherRegimen($site_id, $this->getStartDateForFilter(), $this->getEndDateForFilter());
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
        return $this->render('organization/site/test_outcomes_mother_regimen.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
        ]);
    }

    /**
     * @Route("/organization/site/infant_arv/{site_id?0}", name="app_eid_site_outcomes_infant_arv",requirements={"site_id"="\d+"})
     */
    public function eidOutcomesByInfantARV(TranslatorInterface $translator, int $site_id) {
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
        $outcomes = $this->getDoctrine()->getRepository(\App\Entity\Site::class)->getEidOutcomesByInfantARV($site_id, $this->getStartDateForFilter(), $this->getEndDateForFilter());

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
        return $this->render('organization/site/test_outcomes_infant_arv.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
        ]);
    }

    /**
     * @Route("/organization/site/stats_age/{type?1}/{site_id?0}", name="app_org_site_stats_age",requirements={"site_id"="\d+","type"="[1-4]"})
     */
    public function siteStatsByAge(TranslatorInterface $translator, int $type = 1, int $site_id = 0) {
        switch ($type) {
            case 1: 
                $rows = $this->getStatsByClinicType($translator, $site_id, $this->getStartDateForFilter(), $this->getEndDateForFilter());
                $type = $translator->trans('Service de provenance');
                break;
            case 2:
                $rows = $this->getStatsByMotherHIVStatus($translator, $site_id, $this->getStartDateForFilter(), $this->getEndDateForFilter());
                $type = $translator->trans('Statut VIH de la mère');
                break;
            case 3:
                $rows = $this->getStatsByMotherRegimen($translator, $site_id, $this->getStartDateForFilter(), $this->getEndDateForFilter());
                $type = $translator->trans('Régime de la mère au cours de la PTME');
                break;
            case 4:
                $rows = $this->getStatsByInfantARV($translator, $site_id, $this->getStartDateForFilter(), $this->getEndDateForFilter());
                $type = $translator->trans('Prophylaxie ARV de l\'enfant');
                break;
            default :
                $rows = $this->getStatsByClinicType($translator, $site_id, $this->getStartDateForFilter(), $this->getEndDateForFilter());
                $type = $translator->trans('Service de provenance');
        }
//        echo '<pre>';
//        print_r($statsByClinicType);
//        die();
        return $this->render('organization/site/tab_stats_age_detailed.html.twig', [
                    'stats' => $rows,
                    'type' => $type,
        ]);
    }

    protected function getStatsByClinicType(?TranslatorInterface $translator, $site_id, $start, $end) {
        $ages = $this->getAgeInfos($translator)['ages'];
        $outcomes = $this->getDoctrine()->getRepository(\App\Entity\Site::class)->getAgeStatsByClinicType($site_id, $start, $end);
        $types = $this->getTypeOfClinicInfos();
        $d = [];
        $v = 0;
        foreach ($types as $type) {
            $u = 0;
            $tot = 0;
            $pos = 0;
            $inv = 0;
            foreach ($ages as $age) {
                $d[$v][$u]['total'] = 0;
                $d[$v][$u]['positif'] = 0;
                foreach ($outcomes as $entry) {
                    if (($entry['age_month'] >= $age['limits'][0] && $entry['age_month'] < $age['limits'][1]) && $entry['clinic'] == $type) {
                        $d[$v][$u]['total'] += intval($entry['total']);
                        $d[$v][$u]['positif'] += intval($entry['positif']);
                        $tot += intval($entry['total']);
                        $pos += intval($entry['positif']);
                        $inv += intval($entry['invalide']);
                    }
                }
                $u++;
            }
            $d[$v]['total'] = $tot;
            $d[$v]['positif'] = $pos;
            $d[$v]['invalide'] = $inv;
            $d[$v]['name'] = $translator->trans($type);
            $v++;
        }
        //for null entry for clinic type
        $u = 0;
        $tot = 0;
        $pos = 0;
        $inv = 0;
        foreach ($ages as $age) {
            $d[$v][$u]['total'] = 0;
            $d[$v][$u]['positif'] = 0;
            foreach ($outcomes as $entry) {
                if (($entry['age_month'] >= $age['limits'][0] && $entry['age_month'] < $age['limits'][1]) && is_null($entry['clinic'])) {
                    $d[$v][$u]['total'] += intval($entry['total']);
                    $d[$v][$u]['positif'] += intval($entry['positif']);
                    $tot += intval($entry['total']);
                    $pos += intval($entry['positif']);
                    $inv += intval($entry['invalide']);
                }
            }
            $u++;
        }
        $d[$v]['total'] = $tot;
        $d[$v]['positif'] = $pos;
        $d[$v]['invalide'] = $inv;
        $d[$v]['name'] = $translator->trans('Non défini');
        return $d;
    }

    protected function getStatsByMotherHIVStatus(?TranslatorInterface $translator, $site_id, $start, $end) {
        $ages = $this->getAgeInfos($translator)['ages'];
        $outcomes = $this->getDoctrine()->getRepository(\App\Entity\Site::class)->getAgeStatsByMotherHIVStatus($site_id, $start, $end);
        $types = $this->getMotherHIVStatusInfos();
        $d = [];
        $v = 0;
        foreach ($types as $type) {
            $u = 0;
            $tot = 0;
            $pos = 0;
            $inv = 0;
            foreach ($ages as $age) {
                $d[$v][$u]['total'] = 0;
                $d[$v][$u]['positif'] = 0;
                foreach ($outcomes as $entry) {
                    if (($entry['age_month'] >= $age['limits'][0] && $entry['age_month'] < $age['limits'][1]) && $entry['mother_hiv_status'] == $type) {
                        $d[$v][$u]['total'] += intval($entry['total']);
                        $d[$v][$u]['positif'] += intval($entry['positif']);
                        $tot += intval($entry['total']);
                        $pos += intval($entry['positif']);
                        $inv += intval($entry['invalide']);
                    }
                }
                $u++;
            }
            $d[$v]['total'] = $tot;
            $d[$v]['positif'] = $pos;
            $d[$v]['invalide'] = $inv;
            $d[$v]['name'] = $translator->trans($type);
            $v++;
        }
        //for null entry for clinic type
        $u = 0;
        $tot = 0;
        $pos = 0;
        $inv = 0;
        foreach ($ages as $age) {
            $d[$v][$u]['total'] = 0;
            $d[$v][$u]['positif'] = 0;
            foreach ($outcomes as $entry) {
                if (($entry['age_month'] >= $age['limits'][0] && $entry['age_month'] < $age['limits'][1]) && is_null($entry['mother_hiv_status'])) {
                    $d[$v][$u]['total'] += intval($entry['total']);
                    $d[$v][$u]['positif'] += intval($entry['positif']);
                    $tot += intval($entry['total']);
                    $pos += intval($entry['positif']);
                    $inv += intval($entry['invalide']);
                }
            }
            $u++;
        }
        $d[$v]['total'] = $tot;
        $d[$v]['positif'] = $pos;
        $d[$v]['invalide'] = $inv;
        $d[$v]['name'] = $translator->trans('Non défini');
        return $d;
    }

    protected function getStatsByMotherRegimen(?TranslatorInterface $translator, $site_id, $start, $end) {
        $ages = $this->getAgeInfos($translator)['ages'];
        $outcomes = $this->getDoctrine()->getRepository(\App\Entity\Site::class)->getAgeStatsByMotherRegimen($site_id, $start, $end);
        $types = $this->getMotherRegimentInfos();
        $d = [];
        $v = 0;
        foreach ($types as $type) {
            $u = 0;
            $tot = 0;
            $pos = 0;
            $inv = 0;
            foreach ($ages as $age) {
                $d[$v][$u]['total'] = 0;
                $d[$v][$u]['positif'] = 0;
                foreach ($outcomes as $entry) {
                    if (($entry['age_month'] >= $age['limits'][0] && $entry['age_month'] < $age['limits'][1]) && $entry['mother_regimen'] == $type) {
                        $d[$v][$u]['total'] += intval($entry['total']);
                        $d[$v][$u]['positif'] += intval($entry['positif']);
                        $tot += intval($entry['total']);
                        $pos += intval($entry['positif']);
                        $inv += intval($entry['invalide']);
                    }
                }
                $u++;
            }
            $d[$v]['total'] = $tot;
            $d[$v]['positif'] = $pos;
            $d[$v]['invalide'] = $inv;
            $d[$v]['name'] = $translator->trans($type);
            $v++;
        }
        //for null entry for clinic type
        $u = 0;
        $tot = 0;
        $pos = 0;
        $inv = 0;
        foreach ($ages as $age) {
            $d[$v][$u]['total'] = 0;
            $d[$v][$u]['positif'] = 0;
            foreach ($outcomes as $entry) {
                if (($entry['age_month'] >= $age['limits'][0] && $entry['age_month'] < $age['limits'][1]) && is_null($entry['mother_regimen'])) {
                    $d[$v][$u]['total'] += intval($entry['total']);
                    $d[$v][$u]['positif'] += intval($entry['positif']);
                    $tot += intval($entry['total']);
                    $pos += intval($entry['positif']);
                    $inv += intval($entry['invalide']);
                }
            }
            $u++;
        }
        $d[$v]['total'] = $tot;
        $d[$v]['positif'] = $pos;
        $d[$v]['invalide'] = $inv;
        $d[$v]['name'] = $translator->trans('Non défini');
        return $d;
    }

    protected function getStatsByInfantARV(?TranslatorInterface $translator, $site_id, $start, $end) {
        $ages = $this->getAgeInfos($translator)['ages'];
        $outcomes = $this->getDoctrine()->getRepository(\App\Entity\Site::class)->getAgeStatsByInfantARV($site_id, $start, $end);
        $types = $this->getInfantARVInfos();
        $d = [];
        $v = 0;
        foreach ($types as $type) {
            $u = 0;
            $tot = 0;
            $pos = 0;
            $inv = 0;
            foreach ($ages as $age) {
                $d[$v][$u]['total'] = 0;
                $d[$v][$u]['positif'] = 0;
                foreach ($outcomes as $entry) {
                    if (($entry['age_month'] >= $age['limits'][0] && $entry['age_month'] < $age['limits'][1]) && $entry['infant_arv'] == $type) {
                        $d[$v][$u]['total'] += intval($entry['total']);
                        $d[$v][$u]['positif'] += intval($entry['positif']);
                        $tot += intval($entry['total']);
                        $pos += intval($entry['positif']);
                        $inv += intval($entry['invalide']);
                    }
                }
                $u++;
            }
            $d[$v]['total'] = $tot;
            $d[$v]['positif'] = $pos;
            $d[$v]['invalide'] = $inv;
            $d[$v]['name'] = $translator->trans($type);
            $v++;
        }
        //for null entry for clinic type
        $u = 0;
        $tot = 0;
        $pos = 0;
        $inv = 0;
        foreach ($ages as $age) {
            $d[$v][$u]['total'] = 0;
            $d[$v][$u]['positif'] = 0;
            foreach ($outcomes as $entry) {
                if (($entry['age_month'] >= $age['limits'][0] && $entry['age_month'] < $age['limits'][1]) && is_null($entry['infant_arv'])) {
                    $d[$v][$u]['total'] += intval($entry['total']);
                    $d[$v][$u]['positif'] += intval($entry['positif']);
                    $tot += intval($entry['total']);
                    $pos += intval($entry['positif']);
                    $inv += intval($entry['invalide']);
                }
            }
            $u++;
        }
        $d[$v]['total'] = $tot;
        $d[$v]['positif'] = $pos;
        $d[$v]['invalide'] = $inv;
        $d[$v]['name'] = $translator->trans('Non défini');
        return $d;
    }

}
