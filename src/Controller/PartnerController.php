<?php

#src/Controller/PartnerController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of PartnerController
 *
 * @author PKom
 */
class PartnerController extends AbstractController {

    /**
     * @Route("/partner", name="app_partner")
     */
    public function index(Request $request) {

        return $this->render('partner/index.html.twig');
    }

}
