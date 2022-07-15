<?php

namespace App\Controller\Back;

use App\Entity\Address;
use App\Entity\User;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminAdressesController extends AbstractController
{
    #[Route('/admin/addresses', name: 'app_admin_addresses_index')]
    public function index(EntityManagerInterface $em): Response
    {
        return $this->render('back/addresses/index.html.twig', [
            'addresses' => $em->getRepository(Address::class)->findAll(),
        ]);
    }

    #[Route('/admin/addresses/add', name: 'app_admin_addresses_add', methods: ["GET", "POST"])]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($address);
            $em->flush();
            return $this->redirectToRoute('app_admin_addresses_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/addresses/form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/admin/addresses/edit/{id}', name: 'app_admin_addresses_edit', methods: ["GET", "POST" ,"PUT"])]
    public function edit(Address $address, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
            return $this->redirectToRoute('app_admin_addresses_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/addresses/form.html.twig', [
            'form' => $form->createView(),
            'address' => $address,
        ]);
    }

    #[Route('/admin/addresses/delete/{id}', name: 'app_admin_addresses_delete', methods: ["GET", "POST" ,"PUT"])]
    public function delete(Address $address, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$address->getId(), $request->request->get('_token'))) {
            $em->remove($address);
            $em->flush();
        }
        return $this->redirectToRoute('app_admin_addresses_index', [], Response::HTTP_SEE_OTHER);
    }
}
