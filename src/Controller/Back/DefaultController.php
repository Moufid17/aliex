<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/admin', name: 'default_admin_index')]
    public function index(): Response
    {
        return $this->render('back/default/index.html.twig');
    }
}
