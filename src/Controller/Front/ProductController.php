<?php

namespace App\Controller\Front;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\ProductType;
use App\Form\SearchType;
use App\Security\ProductVoter;
use App\Repository\ProductRepository;
use App\Services\Search;
use ContainerFRB68Wn\PaginatorInterface_82dac15;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class ProductController extends AbstractController
{
    #[Route('/produits', name: 'app_product', methods: ['GET', 'POST'])]
    public function index(Request $request,EntityManagerInterface $entityManager, PaginatorInterface  $paginator): Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
    

        $product = $entityManager->getRepository(Product::class)->findAll();
    
        if($form->isSubmitted() && $form->isValid()){
            $product = $entityManager->getRepository(Product::class)->findBySearch($search);
        };

        $product =$paginator->paginate(
            $product,
            $request->query->getInt("page",1),1
        );
        
        // $product =$paginator->paginate(
        //     $entityManager->getRepository(Product::class)->findBySearch($search),
        //     $request->query->getInt("page",1),12
        // );

        return $this->render('front/product/index.html.twig', [
            'products' => $product,
            'form' => $form->createView(),
        ]);
    }
    /*#[Route('/produits', name: 'app_product', methods: ['GET', 'POST'])]
    public function index(Request $request,PaginatorInterface_82dac15 $paginator): Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        $product =$paginator->paginate(
            $this->repository->findBySearch(),
            $request->query->getInt(key:"page",default:1),limit:12
        
        );

       

        return $this->render('front/product/index.html.twig', [
            'products' => $product,
            'form' => $form->createView(),
        ]);
    }*/

    #[Route('/produits/creer', name: 'app_new_product', methods: ['GET','POST'])]
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

    #[Route("/produits/{id}/edit", name: 'app_edit_product', methods: ['GET','POST'])]
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

    #[Route('/produits/{id}', name: 'app_show_product', methods: ['GET','POST'])]
    public function show(Product $product): Response
    {
        return $this->render('front/product/show.html.twig',
            [
                'product' => $product,
            ]
        );
    }
}
