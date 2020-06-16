<?php

#src/Controller/VisitController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Entity\Visitor;

/**
 * Description of VisitController
 *
 * @author PKom
 */
class VisitController extends AbstractController {

    const UNIQUE_VISIT = 3600; //1 hours
    const ONLINE_DURATION = 60; //1 min
    const VIEW_DURATION = 60; //1 min

    private $session;

    public function __construct(SessionInterface $session) {
        $this->session = $session;
    }

    public function index() {
        
    }

    /**
     * @Route("/visit/infos", name="app_visit_infos")
     */
    public function getVisitInfos(Request $request) {
        $page = $request->get('page', $request->server->get('REQUEST_URI'));
        if (null == $this->session->get('sess_id')) {
            $this->session->set('sess_id', $request->cookies->get('PHPSESSID'));
        }
        $id = $request->server->get('REMOTE_ADDR') . '_' . $this->session->get('sess_id');
        if ($page != $request->server->get('REQUEST_URI')) {
            $this->updateVisitInfos($id, $page);
        }
        $visitInfos = $this->getVisitsCount($page);
        return new \Symfony\Component\HttpFoundation\JsonResponse($visitInfos);
    }

    private function getVisitsCount($page) {
        $result = [
            'total_visit' => 0,
            'page_views' => 0,
            'online' => 0,
        ];
        $array_tv = $this->getDoctrine()->getRepository(Visitor::class)->getTotalVisits();
        $array_pv = $this->getDoctrine()->getRepository(\App\Entity\PageVisited::class)->getPageVisits($page);
        $array_onl = $this->getDoctrine()->getRepository(Visitor::class)->getVisitorOnLine(self::ONLINE_DURATION, time());
        if (isset($array_tv)) {
            $result['total_visit'] = str_pad($array_tv[0]['total_visit'], 5, "0", STR_PAD_LEFT);
        }
        if (isset($array_pv)) {
            $result['page_views'] = str_pad($array_pv[0]['page_views'], 5, "0", STR_PAD_LEFT);
        }
        if (isset($array_onl)) {
            $result['online'] = str_pad($array_onl[0]['online'], 2, "0", STR_PAD_LEFT);
        }
        return $result;
    }

    private function updateVisitInfos($address, $page) {
        $em = $this->getDoctrine()->getManager();
        $visitor = $this->getDoctrine()->getRepository(Visitor::class)->findOneByAddress($address);
        if (!is_null($visitor)) {
            if (time() - $visitor->getTimestamp() >= self::UNIQUE_VISIT) {
                $visitor->setVisitedCount($visitor->getVisitedCount() + 1);
            }
            $visitor->setTimestamp(time());
            $visitor->setLastVisitedDate(new \DateTime());
            $em->persist($visitor);
            $pageVisited = $this->getDoctrine()->getRepository(\App\Entity\PageVisited::class)->findOneBy(['visitor' => $visitor->getId(), 'page' => $page]);
            if (!is_null($pageVisited)) {
                if (time() - $pageVisited->getTimestamp() >= self::UNIQUE_VISIT) {
                    $pageVisited->setVisitedCount($pageVisited->getVisitedCount() + 1);
                }
                $pageVisited->setTimestamp(time());
                $pageVisited->setLastVisitedDate(new \DateTime());
                $em->persist($pageVisited);
            } else {
                $this->addNewPageVisit($page, $visitor);
            }
        } else {
            $this->addNewVisit($address, $page);
        }
        $em->flush();
    }

    private function addNewVisit($address, $page) {
        $visitor = new Visitor();
        $visitor->setVisitedCount(1)
                ->setFirstVisitedDate(new \DateTime())
                ->setYearmonth(intval(date('Ym')))
                ->setIPAddress($address)
                ->setLastVisitedDate(new \DateTime())
                ->setTimestamp(time())
        ;
        $em = $this->getDoctrine()->getManager();
        $em->persist($visitor);
        $this->addNewPageVisit($page, $visitor);
        $em->flush();
    }

    private function addNewPageVisit($page, Visitor $visit) {
        $pageVisited = new \App\Entity\PageVisited();
        $pageVisited->setYearmonth(intval(date('Ym')))
                ->setFirstVisitedDate(new \DateTime())
                ->setLastVisitedDate(new \DateTime())
                ->setVisitedCount(1)
                ->setVisitor($visit)
                ->setPage($page)
                ->setTimestamp(time());
        $em = $this->getDoctrine()->getManager();
        $em->persist($pageVisited);
        $em->flush();
    }

}
