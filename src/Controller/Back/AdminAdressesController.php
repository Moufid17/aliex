<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminAdressesController extends AbstractController
{
    #[Route('/admin/addresses', name: 'app_admin_addresses_index')]
    public function addresses(): Response
    {
        return $this->render('back/addresses/index.html.twig');
    }
}
