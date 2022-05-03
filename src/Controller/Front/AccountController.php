<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/mon-compte', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('front/account/index.html.twig');
    }
}
