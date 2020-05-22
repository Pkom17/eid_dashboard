<?php

#src/Controller/SiteController.php

namespace App\Controller;

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
        $rows = $this->getDoctrine()->getRepository(\App\Entity\Site::class)->getEidSitesStats($start, $end,$district_id);
        return $this->render('organization/site/tab_stats_site.html.twig', [
                    'stats' => $rows,
        ]);
    }

    /**
     * @Route("/organization/district_outcomes/{region_id?0}", name="app_org_districts_outcomes",requirements={"region_id"="-?\d+"})
     */
    public function eidOutcomesByDistricts(TranslatorInterface $translator, int $region_id = 0) {
        $districts = $this->getDoctrine()->getRepository(\App\Entity\District::class)->findDistricts();
        $types = [];
        $categories = [];
        $k = 0;
        foreach ($districts as $value) {
            $types[$k] = $value['name'];
            $k++;
        }

        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        $outcomes = $this->getDoctrine()->getRepository(\App\Entity\District::class)->getEidOutcomesByDistrict($start, $end, $region_id);

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
            if (is_null($entry['district']) || $entry['district'] == 'null' || $entry['district'] == '') {
                $categories[$u] = $translator->trans('Aucune donnée');
            } else {
                $categories[$u] = $entry['district'];
            }
            $d[0]['data'][$u] = intval($entry['positif']);
            $d[1]['data'][$u] = intval($entry['negatif']);
            $d[2]['data'][$u] = intval($entry['invalide']);
            if (intval($entry['total']) == 0) {
                $d[3]['data'][$u] = 0;
            } else {
                $d[3]['data'][$u] = floatval(number_format(($d[0]['data'][$u] / (intval($entry['total']))) * 100, 2));
            }
            if ($u >= $col_limit) {
                break;
            }
            $u++;
        }
        return $this->render('organization/district/districts_outcomes.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
        ]);
    }

    /**
     * @Route("/organization/site_outcomes/{district_id?0}", name="app_org_sites_outcomes",requirements={"district_id"="\d+"})
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
     * @Route("/organization/regions/overview", name="app_regions_overview")
     */
    public function regionsOverview(TranslatorInterface $translator) {
        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        return $this->render('organization/region/regions_overview.html.twig', [
                    'start' => $translator->trans(Util::MONTHS[substr($start, 4, 2)]) . ' ' . substr($start, 0, 4),
                    'end' => $translator->trans(Util::MONTHS[substr($end, 4, 2)]) . ' ' . substr($end, 0, 4),
        ]);
    }

    /**
     * @Route("/organization/region/region_details/{region_id?0}", name="app_region_details",requirements={"region_id"="\d+"})
     */
    public function regionDetails(TranslatorInterface $translator, int $region_id = 0) {
        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        $region = $this->getDoctrine()->getRepository(\App\Entity\Region::class)->findOneBy(['id' => $region_id]);
        return $this->render('organization/region/region_details.html.twig', [
                    'region' => $region,
                    'region_id' => $region_id,
                    'start' => $translator->trans(Util::MONTHS[substr($start, 4, 2)]) . ' ' . substr($start, 0, 4),
                    'end' => $translator->trans(Util::MONTHS[substr($end, 4, 2)]) . ' ' . substr($end, 0, 4),
        ]);
    }

    /**
     * @Route("/organization/region/trends/{region_id?0}", name="app_org_region_trends",requirements={"region_id"="\d+"})
     */
    public function testsTrendsRegion(TranslatorInterface $translator, int $region_id = 0) {
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
        $outcomes = $this->getDoctrine()->getRepository(\App\Entity\Region::class)->getTestsTrendsRegion($region_id, $start2, $end);
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

        return $this->render('organization/region/test_trends.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
                    'start' => $translator->trans(Util::MONTHS[substr($start, 4, 2)]) . ' ' . substr($start, 0, 4),
                    'end' => $translator->trans(Util::MONTHS[substr($end, 4, 2)]) . ' ' . substr($end, 0, 4),
        ]);
    }

    /**
     * @Route("/organization/region/outcomes/{region_id?0}", name="app_org_region_outcomes",requirements={"region_id"="\d+"})
     */
    public function regionOutcomes(TranslatorInterface $translator, int $region_id) {
        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        // details informations 
        $details2 = [];
        $details = $this->getDoctrine()->getRepository(\App\Entity\Region::class)->getRegionOutcomesDetails($region_id, $start, $end);
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
        $outcomes = $this->getDoctrine()->getRepository(\App\Entity\Region::class)->getRegionOutcomes($region_id, $start, $end);

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
        return $this->render('home/test_outcomes.html.twig', [
                    'series' => json_encode($d),
                    'details' => $details2,
        ]);
    }

    /**
     * @Route("/organization/region/outcomes_age/{region_id?0}", name="app_org_region_outcomes_age",requirements={"region_id"="\d+"})
     */
    public function regionOutcomesByAge(TranslatorInterface $translator, int $region_id) {
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


        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        $which_pcr = $this->getParameter('default_pcr'); // PCR 1 only
        $outcomes = $this->getDoctrine()->getRepository(\App\Entity\Region::class)->getRegionOutcomesAge($region_id, $start, $end);

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
        return $this->render('organization/region/region_outcomes_age.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
        ]);
    }

    public function regionStatsByDistrict(int $region_id = 0) {
        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        $rows = $this->getDoctrine()->getRepository(\App\Entity\District::class)->getEidDistrictsStats($start, $end, $region_id);
        return $this->render('organization/region/tab_stats_district.html.twig', [
                    'stats' => $rows,
        ]);
    }

    public function regionOutcomesByDistrict(TranslatorInterface $translator, int $region_id) {
        $districts = $this->getDoctrine()->getRepository(\App\Entity\District::class)->findDistrictsByRegion($region_id);
        $types = [];
        //   $categories = [];
        $categories2 = [];
        $k = 0;
        foreach ($districts as $value) {
            $types[$k] = $value['name'];
            $k++;
        }
        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        $outcomes = $this->getDoctrine()->getRepository(\App\Entity\District::class)->getEidOutcomesByDistrict($start, $end,$region_id);

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
            if (is_null($entry['district']) || $entry['district'] == 'null' || $entry['district'] == '') {
                $categories2[$u] = $translator->trans('Aucune donnée');
            } else {
                $categories2[$u] = $entry['district'];
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
        return $this->render('organization/region/region_outcomes_district.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories2),
        ]);
    }


}
