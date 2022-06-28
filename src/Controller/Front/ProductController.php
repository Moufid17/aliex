<?php

namespace App\Controller\Front;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\ProductType;
use App\Security\ProductVoter;
use App\Repository\ProductRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/produits', name: 'app_product', methods: 'GET')]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('front/product/index.html.twig', [
            // 'products' => $productRepository->findBy([],['name' => 'ASC']),
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/produits/creer', name: 'app_new_product', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // New product
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $imageFileData = $form->getData()->getImageFile();
            $originalImageName = $imageFileData->getClientOriginalName();
            $fileSize = $imageFileData->getSize();
            
            $this->getUser()->addProduct($product);
            $product->setOwner($this->getUser());
            $product->setImageName($originalImageName);
            $product->setImageSize($fileSize);

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('app_product', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/product/new.html.twig', [
                'form' => $form,
            ]
        );
    }

    #[Route('/produits/{id}/edit', name: 'app_edit_product', methods: ['GET', 'POST'])]
    #[IsGranted(ProductVoter::EDIT, 'product',"Vous n'êtes pas autoriser à modifier ce produit.")]
    public function edit(Product $product, ProductRepository $productRepository, Request $request, EntityManagerInterface $em): Response
    {  

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->flush();

            return $this->redirectToRoute('app_product', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/product/edit.html.twig', [
                'form' => $form,
                'product' => $product,
            ]
        );
    }

    #[Route('/produits/{id}', name: 'app_show_product', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('front/product/show.html.twig',
            [
                'product' => $product,
            ]
        );
    }
}
