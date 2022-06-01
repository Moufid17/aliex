<?php

namespace App\Controller\Front;

use App\Form\ChangePasswordType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

// Test isGranted : denied access for some controller 
// use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
// 
// #[IsGranted('ROLE_ADMIN')]
class AccountController extends AbstractController
{
    #[Route('/mon-compte', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('front/account/index.html.twig');
    }

    #[Route('/mon-compte/modifier-mot-de-passe', name: 'app_account_cpwd', methods:['GET', 'POST'])]
    public function account_changePassword(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
        // Create Form
        $form = $this->createForm(ChangePasswordType::class, $this->getUser());
        // Handle form
        $form->handleRequest($request);
        // $form->getData() : return all information about the current user. Nothing about form.
        
        if($form->isSubmitted() && $form->isValid()){
            $pwd = $form->get('old_password')->getData();
            // $hash = $passwordHasher->hashPassword($curUser,$pwd);
            // Compare with current password
            if ($passwordHasher->isPasswordValid($this->getUser(),$pwd)){
                $newPwd = $form->get('newpassword')->getData();
                $pwd_hash = $passwordHasher->hashPassword($this->getUser(),$newPwd);
                $this->getUser()->setPassword($pwd_hash);
                $em->flush();
                return $this->redirectToRoute("app_account");
            }else{
                dd([$this->getUser()->getPassword(), $passwordHasher->hashPassword($this->getUser(),$pwd)]);
                die("erreur");
            }
        }
        
        return $this->render('front/account/changepwd.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    #[Route('/mon-compte/offres', name: 'app_account_offers', methods:['GET', 'POST'])]
    public function offers(ProductRepository $productRepository):Response 
    {
        $products = $productRepository->findByOwner($this->getUser());
        return $this->render('front/account/offers.html.twig',
            [
                'products' => $products,
            ]        
        );
    }
}
