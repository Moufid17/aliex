<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(): Response
    {
        $user = new User();

        $register_form = $this->createForm(RegisterType::class, $user);

        return $this->render('front/register/index.html.twig', [
            'register_form' => $register_form->createView(),
        ]);
    }
}
