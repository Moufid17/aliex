<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request,EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();

        $register_form = $this->createForm(RegisterType::class, $user);
        
        $register_form->handleRequest($request);

        if($register_form->isSubmitted() && $register_form->isValid()){
            // Loading data form user instance
            $user = $register_form->getData();
            // Hashing password
            $pwd = $user->getPassword();
            $pwd_hash = $passwordHasher->hashPassword($user,$pwd);
            // Create db and make migration in console : Done
            // Injection de dependence : entity manager Interface : Done
            // Update password
            $user->setPassword($pwd_hash);
            // persist and flush
            // dd($user->getRoles());
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_account', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/register/index.html.twig', [
            'register_form' => $register_form->createView(),
        ]);
    }
}
