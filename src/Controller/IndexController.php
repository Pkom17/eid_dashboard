<?php

#src/Controller/IndexController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of IndexController
 *
 * @author PKom
 */
class IndexController extends AbstractController {

    /**
     * @Route("/", name="app_home")
     */
    public function index(Request $request) {

        return $this->render('home/index.html.twig');
    }


}
