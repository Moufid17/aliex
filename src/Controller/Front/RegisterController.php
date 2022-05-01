<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();

        $register_form = $this->createForm(RegisterType::class, $user);
        
        $register_form->handleRequest($request);

        if($register_form->isSubmitted() && $register_form->isValid()){
            // Load user
            $user = $register_form->getData();
            // Hash password
            $pwd = $user->getPassword();
            $pwd_hash = $passwordHasher->hashPassword($user,$pwd);
            dd([$pwd, $pwd_hash]);
            // Create db and make migration in console : Done
            // Injection de dependence : entity manager Interface
            // persist and flush
        }

        return $this->render('front/register/index.html.twig', [
            'register_form' => $register_form->createView(),
        ]);
    }
}
