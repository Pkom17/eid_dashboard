<?php

#src/Controller/TrendsController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Utilities\Util;

/**
 * Description of TrendsController
 *
 * @author PKom
 */
class TrendsController extends AbstractController {

    /**
     * @Route("/trends", name="app_trends")
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
        $age_categories = $this->getDoctrine()->getRepository(\App\Entity\EIDAgeCategory::class)->getAgesCategoriesNames();
        return $this->render('trends/index.html.twig', [
                    'ages' => $age_categories,
        ]);
    }

    /**
     * @Route("/tests_trends_year/{region_id}/{district_id}/{site_id}/{age_cat}/{which_pcr}", name="trends_by_year",
     * requirements={
     * "region_id"="-?\d+",
     * "district_id"="-?\d+",
     * "site_id"="-?\d+",
     * "age_cat"="-?\d+",
     * "which_pcr"="-?\d+"
     * })
     */
    public function testTrendsByYear(Request $request, TranslatorInterface $translator, int $region_id = 0, int $district_id = 0, int $site_id = 0, int $age_cat = 0, int $which_pcr = 0) {
        $eidRepo = new \App\Repository\EIDTestRepository($this->getDoctrine());
        $year_limit = 5; // limits year to 5
        $end_year = intval(date("Y")); //get current year
        $end = intval($end_year . '01');
        $start2 = intval($end_year - $year_limit . '01');
        //initialize values for age category limit / -1 for unselected age_category
        $age_month_min = -1;
        $age_month_max = -1;
        if ($age_cat != 0) {
            $age_limit = $this->getDoctrine()->getRepository(\App\Entity\EIDAgeCategory::class)->getAgeCategoryLimit($age_cat);
            $age_month_min = $age_limit['age_min'];
            $age_month_max = $age_limit['age_max'];
        }

        $outcomes = $eidRepo->getEIDTrendsByYear($region_id, $district_id, $site_id, $age_month_min, $age_month_max, $which_pcr, $start2, $end);
        $d = [];
        $periodes = [];
        $i = 0;

        for ($j = $year_limit; $j >= 0; $j--) {
            $periodes[] = $end_year - $j;
        }
        $categories = $periodes;
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
                if ($entry['year'] == $p) {
                    $d[0]['data'][] = intval($entry['positif']);
                    $d[1]['data'][] = intval($entry['negatif']);
                    $d[2]['data'][] = intval($entry['invalide']);
                    $total = intval($entry['negatif']) + intval($entry['positif']) + intval($entry['invalide']);
                    if ($total === 0) {
                        $d[3]['data'][] = 0;
                    } else {
                        $d[3]['data'][] = floatval(number_format((intval($entry['positif']) / ($total)) * 100, 2));
                    }
                    continue(2);
                }
            }
            $d[0]['data'][] = null;
            $d[1]['data'][] = null;
            $d[2]['data'][] = null;
            $d[3]['data'][] = null;
        }
        return $this->render('trends/trends_year.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
        ]);
    }

    /**
     * @Route("/tests_trends_quarter/{region_id}/{district_id}/{site_id}/{age_cat}/{which_pcr}", name="trends_by_quarter",
     * requirements={
     * "region_id"="-?\d+",
     * "district_id"="-?\d+",
     * "site_id"="-?\d+",
     * "age_cat"="-?\d+",
     * "which_pcr"="-?\d+"
     * })
     * )
     */
    public function testTrendsByQuarter(Request $request, TranslatorInterface $translator, int $region_id = 0, int $district_id = 0, int $site_id = 0, int $age_cat = 0, int $which_pcr = 0) {
        $eidRepo = new \App\Repository\EIDTestRepository($this->getDoctrine());
        $year_limit = 4; // limits year to 12
        $end_year = intval(date("Y")); //get current year
        $start_year = $end_year - $year_limit; //get start year
        $end_month = intval(date("m")); //get current year
        $end = intval($end_year . '01');
        $start2 = intval($end_year - $year_limit . '01');
        //initialize values for age category limit / -1 for unselected age_category
        $age_month_min = -1;
        $age_month_max = -1;
        if ($age_cat != 0) {
            $age_limit = $this->getDoctrine()->getRepository(\App\Entity\EIDAgeCategory::class)->getAgeCategoryLimit($age_cat);
            $age_month_min = $age_limit['age_min'];
            $age_month_max = $age_limit['age_max'];
        }
        $outcomes = $eidRepo->getEIDTrendsByQuarter($region_id, $district_id, $site_id, $age_month_min, $age_month_max, $which_pcr, $start2, $end);

//prepare categories for graph
        $d = [];
        $periodes = [];
        $i = 0;
        for ($j = $start_year; $j <= $end_year; $j++) {
            for ($k = 1; $k <= 4; $k++) {
                $periodes[$i]['year'] = $j;
                $periodes[$i]['quarter'] = $k;
                if ($j == $end_year && $k == $end_month) {
                    break;
                }
                $i++;
            }
        }
//set categories
        $categories = [];
        foreach ($periodes as $v) {
            $categories[] = $v['year'] . '-Q' . $v['quarter'];
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
                if ($entry['year'] == $p['year'] && $entry['quarter'] == $p['quarter']) {
                    $d[0]['data'][] = intval($entry['positif']);
                    $d[1]['data'][] = intval($entry['negatif']);
                    $d[2]['data'][] = intval($entry['invalide']);
                    $total = intval($entry['negatif']) + intval($entry['positif']) + intval($entry['invalide']);
                    if ($total === 0) {
                        $d[3]['data'][] = 0;
                    } else {
                        $d[3]['data'][] = floatval(number_format((intval($entry['positif']) / ($total)) * 100, 2));
                    }
                    continue(2);
                }
            }
            $d[0]['data'][] = null;
            $d[1]['data'][] = null;
            $d[2]['data'][] = null;
            $d[3]['data'][] = null;
        }
        return $this->render('trends/trends_quarter.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
        ]);
    }

    /**
     * @Route("/tests_trends_month/{region_id}/{district_id}/{site_id}/{age_cat}/{which_pcr}", name="trends_by_month"
     * ,requirements={
     * "region_id"="-?\d+",
     * "district_id"="-?\d+",
     * "site_id"="-?\d+",
     * "age_cat"="-?\d+",
     * "which_pcr"="-?\d+"
     * }
     * )
     */
    public function testTrendsByMonth(Request $request, TranslatorInterface $translator, int $region_id = 0, int $district_id = 0, int $site_id = 0, int $age_cat = 0, int $which_pcr = 0) {
        $eidRepo = new \App\Repository\EIDTestRepository($this->getDoctrine());
        $year_limit = 8; // limits year to 8
        $end_year = intval(date("Y")); //get current year
        $start_year = $end_year - $year_limit; //get start year
        $end = intval($end_year . '01');
        $start2 = intval($end_year - $year_limit . '01');
        //initialize values for age category limit / -1 for unselected age_category
        $age_month_min = -1;
        $age_month_max = -1;
        if ($age_cat != 0) {
            $age_limit = $this->getDoctrine()->getRepository(\App\Entity\EIDAgeCategory::class)->getAgeCategoryLimit($age_cat);
            $age_month_min = $age_limit['age_min'];
            $age_month_max = $age_limit['age_max'];
        }

        $outcomes = $eidRepo->getEIDTrendsByMonth($region_id, $district_id, $site_id, $age_month_min, $age_month_max, $which_pcr, $start2, $end);
        $d = [];
        $i = 0;

////prepare categories
        $categories = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        foreach ($months as $m) {
            $categories[] = $translator->trans($m);
        }
//prepare series data
        $j = 0;
        for ($k = $start_year; $k <= $end_year; $k++) {
            $d[$j]['name'] = $k;
            $d[$j]['type'] = 'line';
            $d[$j]['tooltip']['valueSuffix'] = " %";
            for ($n = 0; $n < 12; $n++) {
                $d[$j]['data'][$n] = null;
                foreach ($outcomes as $entry) {
                    if ($entry['year'] == $k && $entry['month'] == ($n + 1)) {
                        $total = intval($entry['negatif']) + intval($entry['positif']) + intval($entry['invalide']);
                        if ($total === 0) {
                            $d[$j]['data'][$n] = 0;
                        } else {
                            $d[$j]['data'][$n] = floatval(number_format((intval($entry['positif']) / ($total)) * 100, 2));
                        }
                        continue(2);
                    }
                }
            }
            $j++;
        }
        return $this->render('trends/trends_month.html.twig', [
                    'series' => json_encode($d),
                    'categories' => json_encode($categories),
        ]);
    }

}
