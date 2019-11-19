<?php

#src/Controller/TrendsController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of TrendsController
 *
 * @author PKom
 */
class TrendsController extends AbstractController {

    /**
     * @Route("/trends", name="app_trends")
     */
    public function index(Request $request) {

        return $this->render('trends/index.html.twig');
    }

}
