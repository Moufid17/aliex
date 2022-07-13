<?php

namespace App\Services;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;


class MailerService extends AbstractController

{

    #[Route('/email')]
    public function sendEmail(MailerInterface $mailer): Response


    {
        $user = new User();

        $email = (new Email())
            ->from('aliexesgi2022app@gmail.com')
            ->to($user->getEmail())
            ->subject('Bienvenue sur Aliex')
            ->text('Bienvenue Sur Aliex')
            ->html("<p> Bienvenue chez Aliex </p>{$user->getFirstName()}");

        $mailer->send($email);
    }
}
