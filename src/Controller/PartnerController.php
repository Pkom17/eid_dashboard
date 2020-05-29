<?php

#src/Controller/PartnerController.php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Utilities\Util;

/**
 * Description of PartnerController
 *
 * @author PKom
 */
class PartnerController extends OrganizationController {

    /**
     * @Route("/partner", name="app_partner")
     */
    public function index(TranslatorInterface $translator) {
        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();

        return $this->render('partner/index.html.twig', [
                    'start' => $translator->trans(Util::MONTHS[substr($start, 4, 2)]) . ' ' . substr($start, 0, 4),
                    'end' => $translator->trans(Util::MONTHS[substr($end, 4, 2)]) . ' ' . substr($end, 0, 4),
        ]);
    }

    /**
     * @Route("/partner/partners_stat", name="app_partners_stats")
     */
    public function partnersStat() {
        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        $rows = $this->getDoctrine()->getRepository(\App\Entity\Partner::class)->getEidPartnersStats($start, $end);
        return $this->render('partner/tab_stats_partner.html.twig', [
                    'stats' => $rows,
        ]);
    }

    /**
     * @Route("/partner/partners_outcomes", name="app_partners_outcomes")
     */
    public function eidOutcomesByPartners(TranslatorInterface $translator) {
        $partners = $this->getDoctrine()->getRepository(\App\Entity\Partner::class)->findPartners();
        $types = [];
        $categories = [];
        $k = 0;
        foreach ($partners as $value) {
            $types[$k] = $value['name'];
            $k++;
        }

        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        $outcomes = $this->getDoctrine()->getRepository(\App\Entity\Partner::class)->getEidOutcomesByPartner($start, $end);

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
            if (is_null($entry['partner']) || $entry['partner'] == 'null' || $entry['partner'] == '') {
                $categories[$u] = $translator->trans('Aucune donnée');
            } else {
                $categories[$u] = $entry['partner'];
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
        return $this->render('partner/partners_outcomes.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
        ]);
    }

    /**
     * @Route("/partner/overview", name="app_partner_overview")
     */
    public function partnersOverview(TranslatorInterface $translator) {
        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        return $this->render('partner/partners_overview.html.twig', [
                    'start' => $translator->trans(Util::MONTHS[substr($start, 4, 2)]) . ' ' . substr($start, 0, 4),
                    'end' => $translator->trans(Util::MONTHS[substr($end, 4, 2)]) . ' ' . substr($end, 0, 4),
        ]);
    }

    /**
     * @Route("/partner/partner_details/{partner_id?0}", name="app_partner_details",requirements={"partner_id"="\d+"})
     */
    public function partnerDetails(TranslatorInterface $translator, int $partner_id = 0) {
        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        $partner = $this->getDoctrine()->getRepository(\App\Entity\Partner::class)->findOneBy(['id' => $partner_id]);
        return $this->render('partner/partner_details.html.twig', [
                    'partner' => $partner,
                    'partner_id' => $partner_id,
                    'start' => $translator->trans(Util::MONTHS[substr($start, 4, 2)]) . ' ' . substr($start, 0, 4),
                    'end' => $translator->trans(Util::MONTHS[substr($end, 4, 2)]) . ' ' . substr($end, 0, 4),
        ]);
    }

    /**
     * @Route("/partner/trends/{partner_id?0}", name="app_partner_trends",requirements={"partner_id"="\d+"})
     */
    public function testsTrendsPartners(TranslatorInterface $translator, int $partner_id = 0) {
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
        $outcomes = $this->getDoctrine()->getRepository(\App\Entity\Partner::class)->getTestsTrendsPartner($partner_id, $start2, $end);
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

        return $this->render('partner/test_trends.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
                    'start' => $translator->trans(Util::MONTHS[substr($start, 4, 2)]) . ' ' . substr($start, 0, 4),
                    'end' => $translator->trans(Util::MONTHS[substr($end, 4, 2)]) . ' ' . substr($end, 0, 4),
        ]);
    }

    protected function getOutcomesByPCR(?TranslatorInterface $translator, $partner_id, $start, $end) {
        $outcomes = $this->getDoctrine()->getRepository(\App\Entity\Partner::class)->getPartnerOutcomesPCR($partner_id, $start, $end);
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
     * @Route("/partner/outcomes/{partner_id?0}", name="app_partner_outcomes",requirements={"partner_id"="\d+"})
     */
    public function partnerOutcomes(TranslatorInterface $translator, int $partner_id) {
        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        // details informations 
        $details2 = [];
        $details = $this->getDoctrine()->getRepository(\App\Entity\Partner::class)->getPartnerOutcomesDetails($partner_id, $start, $end);
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
        $outcomes = $this->getDoctrine()->getRepository(\App\Entity\Partner::class)->getPartnerOutcomes($partner_id, $start, $end);

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
        return $this->render('partner/test_outcomes.html.twig', [
                    'series_1' => json_encode($d),
                    'series_2' => $this->getOutcomesByPCR($translator, $partner_id, $start, $end),
                    'details' => $details2,
        ]);
    }

    /**
     * @Route("/partner/outcomes_age/{partner_id?0}", name="app_partner_outcomes_age",requirements={"partner_id"="\d+"})
     */
    public function partnerOutcomesByAge(TranslatorInterface $translator, int $partner_id) {
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
        $outcomes = $this->getDoctrine()->getRepository(\App\Entity\Partner::class)->getPartnerOutcomesAge($partner_id, $start, $end);

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
        return $this->render('partner/partner_outcomes_age.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
        ]);
    }

    /**
     * @Route("/partner/stats_site/{partner_id?0}", name="app_partner_stats_site",requirements={"partner_id"="\d+"})
     */
    public function partnerStatsBySite(int $partner_id = 0) {
        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        $rows = $this->getDoctrine()->getRepository(\App\Entity\Partner::class)->getEidSitesStats($start, $end, $partner_id);
        return $this->render('partner/tab_stats_partner_sites.html.twig', [
                    'stats' => $rows,
        ]);
    }

    /**
     * @Route("/partner/outcomes_site/{partner_id?0}", name="app_partner_outcomes_site",requirements={"partner_id"="\d+"})
     */
    public function partnerOutcomesBySite(TranslatorInterface $translator, int $partner_id) {
        $categories = [];
        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        $outcomes = $this->getDoctrine()->getRepository(\App\Entity\Partner::class)->getEidSitesStats($start, $end, $partner_id);

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
            $u++;
        }
        return $this->render('partner/partner_outcomes_site.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
        ]);
    }
    
    
    /**
     * @Route("/partner/pcr2_reason/{partner_id?0}", name="app_partner_pcr2_reason",requirements={"partner_id"="\d+"})
     */
    public function partnerOutcomesByPCR2Reason(TranslatorInterface $translator, int $partner_id) {
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
        $outcomes = $this->getDoctrine()->getRepository(\App\Entity\Partner::class)->getPartnerOutcomesByPCR2Reason($partner_id, $start, $end);

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
        return $this->render('partner/test_outcomes_pcr2_reason.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
        ]);
    }

    
    /**
     * @Route("/partner/clinic_type/{partner_id?0}", name="app_partner_outcomes_clinic_type",requirements={"partner_id"="\d+"})
     */
    public function eidOutcomesByClinicType(TranslatorInterface $translator, int $partner_id) {
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
        $outcomes = $this->getDoctrine()->getRepository(\App\Entity\Partner::class)->getEidOutcomesByClinicType($partner_id, $this->getStartDateForFilter(), $this->getEndDateForFilter());

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
        return $this->render('partner/test_outcomes_clinic.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
        ]);
    }

    /**
     * @Route("/partner/mother_status/{partner_id?0}", name="app_partner_outcomes_mother_status",requirements={"partner_id"="\d+"})
     */
    public function eidOutcomesMotherStatus(TranslatorInterface $translator, int $partner_id) {
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
        $outcomes = $this->getDoctrine()->getRepository(\App\Entity\Partner::class)->getEidOutcomesByMotherStatus($partner_id, $this->getStartDateForFilter(), $this->getEndDateForFilter());

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
        return $this->render('partner/test_outcomes_mother_status.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
        ]);
    }

    /**
     * @Route("/partner/mother_regimen/{partner_id?0}", name="app_partner_outcomes_monther_regimen",requirements={"partner_id"="\d+"})
     */
    public function eidOutcomesByMotherRegimen(TranslatorInterface $translator, int $partner_id) {
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
        $outcomes = $this->getDoctrine()->getRepository(\App\Entity\Partner::class)->getEidOutcomesByMotherRegimen($partner_id, $this->getStartDateForFilter(), $this->getEndDateForFilter());
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
        return $this->render('partner/test_outcomes_mother_regimen.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
        ]);
    }

    /**
     * @Route("/partner/infant_arv/{partner_id?0}", name="app_partner_outcomes_infant_arv",requirements={"partner_id"="\d+"})
     */
    public function eidOutcomesByInfantARV(TranslatorInterface $translator, int $partner_id) {
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
        $outcomes = $this->getDoctrine()->getRepository(\App\Entity\Partner::class)->getEidOutcomesByInfantARV($partner_id, $this->getStartDateForFilter(), $this->getEndDateForFilter());

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
        return $this->render('partner/test_outcomes_infant_arv.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
        ]);
    }


}
