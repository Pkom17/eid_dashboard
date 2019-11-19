<?php

#src/Controller/PositivityController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of PositivityController
 *
 * @author PKom
 */
class PositivityController extends AbstractController {

    /**
     * @Route("/positivity", name="app_positivity")
     */
    public function index(Request $request) {

        return $this->render('positivity/index.html.twig');
    }

}
