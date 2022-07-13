<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\Transports;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;


class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer): Response
    {
        $user = new User();

        $register_form = $this->createForm(RegisterType::class, $user);

        $register_form->handleRequest($request);

        if ($register_form->isSubmitted() && $register_form->isValid()) {
            // Loading data form user instance
            $user = $register_form->getData();
            // Hashing password
            $pwd = $user->getPassword();
            $pwd_hash = $passwordHasher->hashPassword($user, $pwd);
            $user->setPassword($pwd_hash);
            $entityManager->persist($user);
            $entityManager->flush();

            // Sending registration mail 
            $email = (new TemplatedEmail())
                ->from('aliexesgi2022app@gmail.com')
                ->to($user->getEmail())
                ->subject('Bienvenue sur Aliex')
                ->text('Bienvenue Sur Aliex')
                ->htmlTemplate('emails/register.html.twig');
            $mailer->send($email);


            return $this->redirectToRoute('app_account', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/register/index.html.twig', [
            'register_form' => $register_form->createView(),
        ]);
    }
}
