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
class OrganizationController extends AbstractController {

    /**
     * @Route("/organization", name="app_organization")
     */
    public function index(Request $request) {

        return $this->render('organization/index.html.twig');
    }

}
