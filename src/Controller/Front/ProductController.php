<?php

namespace App\Controller\Front;
use App\Entity\User;
use App\Entity\Category;
use App\Entity\Product;
use App\Form\ProductType;
use App\Form\SearchType;
use App\Security\ProductVoter;
use App\Repository\ProductRepository;
use App\Services\Search;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\Transports;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;


class ProductController extends AbstractController
{
    #[Route('/produits', name: 'app_product', methods: ['GET', 'POST'])]
    public function index(Request $request,EntityManagerInterface $entityManager,): Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        $product = $entityManager->getRepository(Product::class)->findAll();

        if($form->isSubmitted() && $form->isValid()){
            $product = $entityManager->getRepository(Product::class)->findBySearch($search);
        }

        return $this->render('front/product/index.html.twig', [
            'products' => $product,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/produits/creer', name: 'app_new_product', methods: ['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // New product
        $user = new User();
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

            $email = (new TemplatedEmail())

                ->from('aliexesgi2022app@gmail.com')
                ->to($this->getUser()->getEmail())
                ->subject('Votre produit a bien été ajouté')
                ->htmlTemplate('emails/addProducts.html.twig')
                ->context([
                    'username' => $this->getUser()->getUsername(),
                    'product_name' => $product->getName(),
                ]);
            $mailer->send($email);

            return $this->redirectToRoute('app_product', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/product/new.html.twig', [
                'form' => $form,
            ]
        );
    }

    #[Route("/produits/{id}/edit", name: 'app_edit_product', methods: ['GET','POST'])]
    #[IsGranted(ProductVoter::EDIT, 'product',"Vous n'êtes pas autoriser à modifier ce produit.")]
    public function edit(Product $product, ProductRepository $productRepository, Request $request, EntityManagerInterface $em, MailerInterface $mailer): Response
    {

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
            $email = (new TemplatedEmail())

                ->from('aliexesgi2022app@gmail.com')
                ->to($this->getUser()->getEmail())
                ->subject('Votre produit a bien été ajouté')
                ->htmlTemplate('emails/editProducts.html.twig')
                ->context([
                    'username' => $this->getUser()->getUsername(),
                    'product_name' => $product->getName(),
               ]);
            $mailer->send($email);

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

    #[Route('/produits/delete/{id}', name: 'app_delete_product')]
    public function delete(Product $product, Request $request, EntityManagerInterface $entityManager,MailerInterface $mailer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $this->getUser()->removeProduct($product);
            $entityManager->remove($product);
            $entityManager->flush();

            $email = (new TemplatedEmail())

                ->from('aliexesgi2022app@gmail.com')
                ->to($this->getUser()->getEmail())
                ->subject('Votre produit a bien été ajouté')
                ->htmlTemplate('emails/deleteProducts.html.twig')
                ->context([
                'username' => $this->getUser()->getUsername(),
                'product_name' => $product->getName(),
            ]);

            $mailer->send($email);
        }
        return $this->redirectToRoute('app_account_offers', [], Response::HTTP_SEE_OTHER);
    }
}
