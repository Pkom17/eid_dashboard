<?php

#src/Controller/PartnerController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Utilities\Util;

/**
 * Description of PartnerController
 *
 * @author PKom
 */
class PartnerController extends AbstractController {

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

    private function getStartDateForFilter() {
        $start = $this->get('session')->get("startDate");
        if (null === $start) {
            $start = intval((intval(date("Y")) - 1) . date("m"));
        }
        return $start;
    }

    private function getEndDateForFilter() {
        $end = $this->get('session')->get("endDate");
        if (null === $end) {
            $end = intval(date("Ym"));
        }
        return $end;
    }

}
