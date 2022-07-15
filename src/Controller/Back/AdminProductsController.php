<?php

namespace App\Controller\Back;

use App\Entity\Address;
use App\Entity\Product;
use App\Entity\User;
use App\Form\AddressType;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminProductsController extends AbstractController
{
    #[Route('/admin/products', name: 'app_admin_products_index')]
    public function index(EntityManagerInterface $em): Response
    {
        return $this->render('back/products/index.html.twig', [
            'products' => $em->getRepository(Product::class)->findAll(),
        ]);
    }

    #[Route('/admin/products/add', name: 'app_admin_products_add', methods: ["GET", "POST"])]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $product->setOwner($this->getUser());
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('app_admin_products_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/products/form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/admin/products/edit/{id}', name: 'app_admin_products_edit', methods: ["GET", "POST" ,"PUT"])]
    public function edit(Product $product, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->remove('category');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
            return $this->redirectToRoute('app_admin_products_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/products/form.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);
    }

    #[Route('/admin/products/delete/{id}', name: 'app_admin_products_delete', methods: ["GET", "POST" ,"PUT"])]
    public function delete(Product $product, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $em->remove($product);
            $em->flush();
        }
        return $this->redirectToRoute('app_admin_products_index', [], Response::HTTP_SEE_OTHER);
    }
}
