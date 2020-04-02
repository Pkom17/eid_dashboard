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
        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        $age_categories = $this->getDoctrine()->getRepository(\App\Entity\EIDAgeCategory::class)->getAgesCategoriesNames();
        $eid_plateformes = $this->getDoctrine()->getRepository(\App\Entity\Plateforme::class)->findEIDPlateformes();
        return $this->render('labs/index.html.twig', [
                    'plateformes' => $eid_plateformes,
                    'ages' => $age_categories,
                    'start' => $translator->trans(Util::MONTHS[substr($start, 4, 2)]) . ' ' . substr($start, 0, 4),
                    'end' => $translator->trans(Util::MONTHS[substr($end, 4, 2)]) . ' ' . substr($end, 0, 4),
        ]);
    }

    /**
     * @Route("/labs/labs_stat", name="app_labs_stats")
     */
    public function labsStat(Request $request, TranslatorInterface $translator) {
        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        $rows = $this->getDoctrine()->getRepository(\App\Entity\Plateforme::class)->getEidOutcomesLabsStats($start, $end);
        return $this->render('labs/stats.html.twig', [
                    'stats' => $rows,
        ]);
    }

    /**
     * @Route("/labs/labs_stat_age/{plateforme}/{age_cat}/{which_pcr}", name="app_labs_stats_age",
     * requirements={"which_pcr"="-?\d+","age_cat"="-?\d+","plateforme"="-?\d+"})
     */
    public function labsStatAge(Request $request, TranslatorInterface $translator, int $plateforme = 0, int $age_cat = 0, int $which_pcr = 0) {
        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();
        $age_month_min = -1;
        $age_month_max = -1;
        $age_cat_name = "";
        if ($age_cat != 0) {
            $age_limit = $this->getDoctrine()->getRepository(\App\Entity\EIDAgeCategory::class)->getAgeCategoryLimit($age_cat);
            $age_cat_name = $age_limit['name'];
        }
        if ($age_cat != 0 && $plateforme == 0) {
            $age_month_min = $age_limit['age_min'];
            $age_month_max = $age_limit['age_max'];
            return $this->labsStatByOneAgeCat($request, $translator, $age_cat_name, $age_month_min, $age_month_max, $which_pcr, $start, $end);
        } elseif ($age_cat == 0 && $plateforme == 0) {
            return $this->labsStatByAllAgeCat($request, $translator, $age_month_min, $age_month_max, $which_pcr, $start, $end);
        } elseif ($age_cat != 0 && $plateforme != 0) {
            return $this->labStatByOneAgeCat($request, $translator, $plateforme, $age_cat_name, $age_month_min, $age_month_max, $which_pcr, $start, $end);
        } elseif ($age_cat == 0 && $plateforme != 0) {
            return $this->labStatByAllAgeCat($request, $translator, $plateforme, $age_month_min, $age_month_max, $which_pcr, $start, $end);
        }
    }

    private function labsStatByOneAgeCat(Request $request, TranslatorInterface $translator, string $age_cat_name, int $age_month_min, int $age_month_max, int $which_pcr, int $start, int $end) {
        $rows = $this->getDoctrine()->getRepository(\App\Entity\Plateforme::class)->getEidOutcomesLabsAge($which_pcr, 0, $age_month_min, $age_month_max, $start, $end);
        $plateformes = $this->getDoctrine()->getRepository(\App\Entity\Plateforme::class)->findEIDPlateformes();
        $d = [];
        $labs = [];
        foreach ($plateformes as $v) {
            $labs[] = $v['name'];
        }

        $u = 0;
        $d[0]['color'] = $this->getParameter('pos_color');
        $d[1]['color'] = $this->getParameter('neg_color');
        $d[2]['color'] = $this->getParameter('inv_color');

        $d[0]['data'] = [];
        $d[1]['data'] = [];
        $d[2]['data'] = [];
        $d[0]['type'] = 'column';
        $d[1]['type'] = 'column';
        $d[2]['type'] = 'column';
        $d[0]['name'] = $translator->trans('Positive');
        $d[1]['name'] = $translator->trans('Negative');
        $d[2]['name'] = $translator->trans('Invalide');
        foreach ($labs as $lab) {
            $d[0]['data'][$u] = 0;
            $d[1]['data'][$u] = 0;
            $d[2]['data'][$u] = 0;
            foreach ($rows as $row) {
                if ($row['plateforme'] == $lab) {
                    $d[0]['data'][$u] += intval($row['positif']);
                    $d[1]['data'][$u] += intval($row['negatif']);
                    $d[2]['data'][$u] += intval($row['invalide']);
                }
            }
            $u++;
        }
        return $this->render('labs/stats_by_one_age.html.twig', [
                    'label' => $translator->trans($age_cat_name),
                    'series' => json_encode($d),
                    'categories' => json_encode($labs),
        ]);
    }

    private function labStatByOneAgeCat(Request $request, TranslatorInterface $translator, int $plateforme, string $age_cat_name, int $age_month_min, int $age_month_max, int $which_pcr, int $start, int $end) {
        $rows = $this->getDoctrine()->getRepository(\App\Entity\Plateforme::class)->getEidOutcomesLabsAge($which_pcr, $plateforme, $age_month_min, $age_month_max, $start, $end);

        $d = [];
        $d[0]['color'] = $this->getParameter('pos_color');
        $d[1]['color'] = $this->getParameter('neg_color');
        $d[2]['color'] = $this->getParameter('inv_color');
        $d[0]['name'] = $translator->trans('Positive');
        $d[1]['name'] = $translator->trans('Negative');
        $d[2]['name'] = $translator->trans('Invalide');
        $d[0]['y'] = 0;
        $d[1]['y'] = 0;
        $d[2]['y'] = 0;
        foreach ($rows as $row) {
            $d[0]['y'] += intval($row['positif']);
            $d[1]['y'] += intval($row['negatif']);
            $d[2]['y'] += intval($row['invalide']);
        }
        return $this->render('labs/lab_stats_by_one_age.html.twig', [
                    'label' => $translator->trans($age_cat_name),
                    'series' => json_encode($d),
        ]);
    }

    private function labsStatByAllAgeCat(Request $request, TranslatorInterface $translator, int $age_month_min, int $age_month_max, int $which_pcr, int $start, int $end) {
        $rows = $this->getDoctrine()->getRepository(\App\Entity\Plateforme::class)->getEidOutcomesLabsAge($which_pcr, 0, $age_month_min, $age_month_max, $start, $end);
        $plateformes = $this->getDoctrine()->getRepository(\App\Entity\Plateforme::class)->findEIDPlateformes();
        $agesCategories = $this->getDoctrine()->getRepository(\App\Entity\EIDAgeCategory::class)->getAgesCategories();
        $d = [];
        $labs = [];
        $ages = [];
        foreach ($plateformes as $v) {
            $labs[] = $v['name'];
        }

        $k = 0;
        foreach ($agesCategories as $value) {
            $ages[$k]['name'] = $value['name'];
            $ages[$k]['limits'][0] = $value['age_min'];
            $ages[$k]['limits'][1] = $value['age_max'];
            $k++;
        }

        $u = 0;

        $d[0]['color'] = '#0acef5';
        //$d[1]['color'] = '#098da7';
        $d[1]['color'] = '#08fc29';
        //$d[3]['color'] = '#14a127';
        $d[2]['color'] = '#f3c508';
        //$d[7]['color'] = '#b8960c';
        $d[3]['color'] = '#f36060';
        //$d[5]['color'] = '#ec0b0b';
        $d[4]['color'] = '#8464e0';
        //$d[9]['color'] = '#4204f2';
        //$d[5]['color'] = '#8d8c8e';
        //$d[11]['color'] = '#3c3c3c';
        foreach ($ages as $age) {
            $v = 0;
            $d[$u]['data'] = [];
            foreach ($labs as $lab) {
                $d[$u]['name'] = $translator->trans($age['name']);
                $d[$u]['type'] = 'column';
                $d[$u]['data'][$v] = 0;
                foreach ($rows as $row) {
                    if ($row['age_month'] >= $age['limits'][0] && $row['age_month'] < $age['limits'][1] && $row['plateforme'] == $lab) {
                        $d[$u]['data'][$v] += intval($row['positif']) + intval($row['negatif']) + intval($row['invalide']);
                    }
                }
                $v++;
            }
            $u++;
        }
        return $this->render('labs/stats_age.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($labs),
        ]);
    }

    private function labStatByAllAgeCat(Request $request, TranslatorInterface $translator, int $plateforme, int $age_month_min, int $age_month_max, int $which_pcr, int $start, int $end) {
        $agesCategories = $this->getDoctrine()->getRepository(\App\Entity\EIDAgeCategory::class)->getAgesCategories();
        $rows = $this->getDoctrine()->getRepository(\App\Entity\Plateforme::class)->getEidOutcomesLabsAge($which_pcr, $plateforme, $age_month_min, $age_month_max, $start, $end);
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
            foreach ($rows as $entry) {
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
        foreach ($rows as $entry) {
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
        return $this->render('labs/lab_stats_by_all_age.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
        ]);
    }

    /**
     * @Route("/labs/labs_tat/{plateforme}", name="app_labs_tat",requirements={"plateforme"="-?\d+"})
     */
    public function labsTAT(Request $request, TranslatorInterface $translator,$plateforme=0) {
        $start = $this->getStartDateForFilter();
        $end = $this->getEndDateForFilter();

        if($plateforme == 0){
            return $this->allLabsTAT($request, $translator, $start, $end);
        }
        else
        {
            return $this->oneLabTAT($request, $translator,$plateforme, $start, $end);
        }
        
    }

    
    private function allLabsTAT(Request $request, TranslatorInterface $translator, int $start,int $end) {
        $tats_ = $this->getDoctrine()->getRepository(\App\Entity\Plateforme::class)->getAllPlateformesTATs($start, $end);
        $plateformes = $this->getDoctrine()->getRepository(\App\Entity\Plateforme::class)->findEIDPlateformes();
        $labs = [];
        foreach ($plateformes as $v) {
            $labs[] = $v['name'];
        }

        $rows_ = [];
        $x = 0;
        foreach ($plateformes as $plateforme) {
            $y = 0;
            $rows_[$x]['plateforme'] = $plateforme['name'];
            $rows_[$x]['tat1'][$y] = 0;
            $rows_[$x]['tat2'][$y] = 0;
            $rows_[$x]['tat3'][$y] = 0;
            foreach ($tats_ as $tat_) {
                if ($tat_['plateforme'] == $plateforme['name']) {
                    $rows_[$x]['tat1'][$y] = $tat_['tat1'];
                    $rows_[$x]['tat2'][$y] = $tat_['tat2'];
                    $rows_[$x]['tat3'][$y] = $tat_['tat3'];
                    $y++;
                }
            }
            $rows_[$x]['tests'] = $y;
            $x++;
        }
        $x = 0;
        $rows = [];
        foreach ($rows_ as $row_) {
            $rows[$x]['plateforme'] = $row_['plateforme'];
            sort($row_['tat1']);
            $rows[$x]['tat1'] = Util::median($row_['tat1']);
            sort($row_['tat2']);
            $rows[$x]['tat2'] = Util::median($row_['tat2']);
            sort($row_['tat3']);
            $rows[$x]['tat3'] = Util::median($row_['tat3']);
            $tat1_limit = [Util::ct($row_['tat1'][0]), end($row_['tat1'])];
            $tat2_limit = [Util::ct($row_['tat2'][0]), end($row_['tat2'])];
            $tat3_limit = [Util::ct($row_['tat3'][0]), end($row_['tat3'])];
            $rows[$x]['limits'] = [$tat1_limit, $tat2_limit, $tat3_limit];
            $rows[$x]['tests'] = $row_['tests'];
            $x++;
        }

        $u = 0;
        $d[0]['color'] = '#021225';
        $d[1]['color'] = "#FF6666";
        $d[2]['color'] = "#FFFF66";
        $d[3]['color'] = "#66FF66";
        $limits = [];
        $d[0]['data'] = [];
        $d[0]['yAxis'] = 0;
        $d[1]['data'] = [];
        $d[1]['yAxis'] = 1;
        $d[2]['data'] = [];
        $d[2]['yAxis'] = 1;
        $d[3]['data'] = [];
        $d[3]['yAxis'] = 1;
        $d[0]['type'] = 'spline';
        $d[1]['type'] = 'column';
        $d[2]['type'] = 'column';
        $d[3]['type'] = 'column';
        $d[0]['name'] = $translator->trans('Tests effectués');
        $d[1]['name'] = $translator->trans('Du prélèvement à la reception');
        $d[2]['name'] = $translator->trans('De la reception au traitement');
        $d[3]['name'] = $translator->trans('Du traitement à la validation');
        foreach ($labs as $lab) {
            $d[0]['data'][$u] = 0;
            $d[1]['data'][$u] = 0;
            $d[2]['data'][$u] = 0;
            $d[3]['data'][$u] = 0;
            foreach ($rows as $row) {
                if ($row['plateforme'] == $lab) {
                    $limits[$lab]['limits'] = $row['limits'];
                    $d[0]['data'][$u] = intval($row['tests']);
                    $d[1]['data'][$u] = intval($row['tat1']);
                    $d[2]['data'][$u] = intval($row['tat2']);
                    $d[3]['data'][$u] = intval($row['tat3']);
                    break;
                }
            }
            $u++;
        }
        return $this->render('labs/labs_tat.html.twig', [
                    'limits' => $limits,
                    'series' => json_encode($d),
                    'categories' => json_encode($labs),
                    'plateformes' => $plateformes,
        ]);
    }


    private  function oneLabTAT(Request $request, TranslatorInterface $translator, int $plateforme, int $start,int $end) {

        $start_year = intval(substr($start, 0, 4));
        $start_month = intval(substr($start, 4, 2));
        $end_year = intval(substr($end, 0, 4));
        $end_month = intval(substr($end, 4, 2));

        $tats_ = $this->getDoctrine()->getRepository(\App\Entity\Plateforme::class)->getPlateformeTATs($plateforme, $start, $end);

        $periodes = [];
        $i = 0;
        for ($j = $start_year; $j <= $end_year; $j++) {
            for ($k = 1; $k <= 12; $k++) {
                if ($j == $start_year && $k < $start_month) {
                    continue;
                }
                $periodes[$i]['year'] = $j;
                $periodes[$i]['month'] = $k;
                if ($j == $end_year && $k == $end_month) {
                    break;
                }
                $i++;
            }
        }

        $rows_ = [];
        $x = 0;
        foreach ($periodes as $periode) {
            $y = 0;
            $rows_[$x]['year'] = $periode['year'];
            $rows_[$x]['month'] = $periode['month'];
            $rows_[$x]['tat1'][$y] = 0;
            $rows_[$x]['tat2'][$y] = 0;
            $rows_[$x]['tat3'][$y] = 0;
            foreach ($tats_ as $tat_) {
                if ($tat_['year'] == $periode['year'] && $tat_['month'] == $periode['month']) {
                    $rows_[$x]['tat1'][$y] = $tat_['tat1'];
                    $rows_[$x]['tat2'][$y] = $tat_['tat2'];
                    $rows_[$x]['tat3'][$y] = $tat_['tat3'];
                    $y++;
                }
            }
            $rows_[$x]['tests'] = $y;
            $x++;
        }

        $categories = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        foreach ($periodes as $v) {
            $categories[] = $translator->trans($months[$v['month'] - 1]) . '-' . $v['year'];
        }

        $x = 0;
        $rows = [];
        foreach ($rows_ as $row_) {
            $rows[$x]['year'] = $row_['year'];
            $rows[$x]['month'] = $row_['month'];
            sort($row_['tat1']);
            $rows[$x]['tat1'] = Util::median($row_['tat1']);
            sort($row_['tat2']);
            $rows[$x]['tat2'] = Util::median($row_['tat2']);
            sort($row_['tat3']);
            $rows[$x]['tat3'] = Util::median($row_['tat3']);
            $tat1_limit = [Util::ct($row_['tat1'][0]), end($row_['tat1'])];
            $tat2_limit = [Util::ct($row_['tat2'][0]), end($row_['tat2'])];
            $tat3_limit = [Util::ct($row_['tat3'][0]), end($row_['tat3'])];
            $rows[$x]['limits'] = [$tat1_limit, $tat2_limit, $tat3_limit];
            $rows[$x]['tests'] = $row_['tests'];
            $x++;
        }

        $d = [];
        $u = 0;
        $d[0]['color'] = '#021225';
        $d[1]['color'] = "#FF6666";
        $d[2]['color'] = "#FFFF66";
        $d[3]['color'] = "#66FF66";
        $limits = [];
        $d[0]['data'] = [];
        $d[0]['yAxis'] = 0;
        $d[1]['data'] = [];
        $d[1]['yAxis'] = 1;
        $d[2]['data'] = [];
        $d[2]['yAxis'] = 1;
        $d[3]['data'] = [];
        $d[3]['yAxis'] = 1;
        $d[0]['type'] = 'spline';
        $d[1]['type'] = 'column';
        $d[2]['type'] = 'column';
        $d[3]['type'] = 'column';
        $d[0]['name'] = $translator->trans('Tests effectués');
        $d[1]['name'] = $translator->trans('Du prélèvement à la reception');
        $d[2]['name'] = $translator->trans('De la reception au traitement');
        $d[3]['name'] = $translator->trans('Du traitement à la validation');
        foreach ($periodes as $periode) {
            $d[0]['data'][$u] = 0;
            $d[1]['data'][$u] = 0;
            $d[2]['data'][$u] = 0;
            $d[3]['data'][$u] = 0;
            $x_name = $translator->trans($months[$periode['month'] - 1]) . '-' . $periode['year'];
            foreach ($rows as $row) {
                if ($row['year'] == $periode['year'] && $row['month'] == $periode['month']) {
                    $limits[$x_name]['limits'] = $row['limits'];
                    $d[0]['data'][$u] = intval($row['tests']);
                    $d[1]['data'][$u] = intval($row['tat1']);
                    $d[2]['data'][$u] = intval($row['tat2']);
                    $d[3]['data'][$u] = intval($row['tat3']);
                    break;
                }
            }
            $u++;
        }

        return $this->render('labs/lab_tat.html.twig', [
                    'limits' => $limits,
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
        ]);
    }

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

}
