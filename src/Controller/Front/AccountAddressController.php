<?php

namespace App\Controller\Front;

use App\Entity\Address;
use App\Form\AccountAddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAddressController extends AbstractController
{
    #[Route('/mon-compte/mes-addresses', name: 'app_account_user_address')]
    public function index(): Response
    {
        return $this->render('front/account/address_index.html.twig');
    }

    #[Route('/mon-compte/addresses-add', name: 'app_account_user_address_add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $address = new Address();
        $form = $this->createForm(AccountAddressType::class, $address);
        $form->handleRequest($request);
        

        if($form->isSubmitted() && $form->isValid()){
            $this->getUser()->addAddress($address);
            $entityManager->persist($address);
            // $entityManager->persist($this->getUser());
            $entityManager->flush();

            return $this->redirectToRoute('app_account_user_address', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/account/address_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/mon-compte/addresses/edit/{id}', name: 'app_account_user_address_edit')]
    public function edit(Address $address, EntityManagerInterface $entityManager, Request $request): Response
    {
    
        if($entityManager->getRepository(Address::class)->findOneBy(['id'=>$address])){
            $form = $this->createForm(AccountAddressType::class, $address);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $entityManager->flush();
                return $this->redirectToRoute('app_account_user_address', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('front/account/address_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/mon-compte/addresses/delete/{id}', name: 'app_account_user_address_delete')]
    public function delete(Address $address, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete'.$address->getId(), $request->request->get('_token'))) {
            // dd($address);
            $this->getUser()->removeAddress($address);
            $entityManager->remove($address);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_account_user_address', [], Response::HTTP_SEE_OTHER);
    }
}
