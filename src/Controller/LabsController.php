<?php

#src/Controller/OrganizationController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of OrganizationController
 *
 * @author PKom
 */
class LabsController extends AbstractController {

    /**
     * @Route("/labs", name="app_labs")
     */
    public function index(Request $request) {

        return $this->render('labs/index.html.twig');
    }

}
