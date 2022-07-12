<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoriesController extends AbstractController
{
    #[Route('/admin/categories', name: 'app_admin_categories_index')]
    public function categories(): Response
    {
        return $this->render('back/categories/index.html.twig');
    }
}
