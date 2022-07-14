<?php

namespace App\Controller\Back;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoriesController extends AbstractController
{
    #[Route('/admin/categories', name: 'app_admin_categories_index')]
    public function index(EntityManagerInterface $em): Response
    {
        return $this->render('back/categories/index.html.twig', [
            'categories' => $em->getRepository(Category::class)->findAll(),
        ]);
    }

    #[Route('/admin/categories/add', name: 'app_admin_categories_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('app_admin_categories_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/categories/form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/admin/categories/edit/{id}', name: 'app_admin_categories_edit')]
    public function edit(Category $category, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
            return $this->redirectToRoute('app_admin_categories_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/categories/form.html.twig', [
            'form' => $form,
            'category' => $category,
        ]);
    }

    #[Route('/admin/categories/delete/{id}', name: 'app_admin_categories_delete')]
    public function delete(Category $category, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete_'.$category->getId(), $request->request->get('_token'))) {
            foreach ($category->getProducts() as $product) {
                $product->setCategory($em->getRepository(Category::class)->findOneBy(['name' => 'Autres']));
            }
            //email to user having the product $product
            $em->remove($category);
            $em->flush();
        }
        return $this->redirectToRoute('app_admin_categories_index', [], Response::HTTP_SEE_OTHER);
    }
}
