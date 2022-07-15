<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Form\RegisterType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminUsersController extends AbstractController
{
    #[Route('/admin/users', name: 'app_admin_users_index', methods: "GET")]
    public function index(EntityManagerInterface $em): Response
    {
        return $this->render('back/users/index.html.twig', [
            'users' => $em->getRepository(User::class)->findAll(),
        ]);
    }

    #[Route('/admin/users/add-admin', name: 'app_admin_users_add_admin', methods: ["GET", "POST"])]
    public function addAdmin(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->remove('agreeTerms');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user->setPassword($this->hashPassword($user, $passwordHasher));
            $user->setRoles(['ROLE_ADMIN']);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_admin_users_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/users/add-form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/admin/users/add-user', name: 'app_admin_users_add_user', methods: ["GET", "POST"])]
    public function addUser(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->remove('agreeTerms');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user->setPassword($this->hashPassword($user, $passwordHasher));
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_admin_users_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/users/add-form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/admin/users/edit/{id}', name: 'app_admin_users_edit', methods: ["GET", "POST" ,"PUT"])]
    public function edit(User $user, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(UserType::class, $user, [
            'user_roles' => $user->getRoles(),
            //'user_roles' => $this->getUser()->getRoles(),
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
            return $this->redirectToRoute('app_admin_users_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/users/edit-form.html.twig', [
            'form' => $form->createView(),
            //'user' => $user,
        ]);
    }

    #[Route('/admin/categories/delete/{id}', name: 'app_admin_users_delete', methods: ["POST", "DELETE"])]
    public function delete(User $user, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
/*            foreach ($category->getProducts() as $product) {
                $product->setCategory($em->getRepository(Category::class)->findOneBy(['name' => 'Autres']));
            }*/
            $em->remove($user);
            $em->flush();
        }
        return $this->redirectToRoute('app_admin_users_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/admin/categories/ban/{id}', name: 'app_admin_users_ban', methods: ["GET", "POST" ,"PUT"])]
    public function ban(User $user, Request $request, EntityManagerInterface $em): Response
    {
        $user->setBanned(!$user->getBanned());
        $em->flush();
        return $this->redirectToRoute('app_admin_users_index', [], Response::HTTP_SEE_OTHER);
    }

    private function hashPassword(User $user, UserPasswordHasherInterface $passwordHasher): string {
        $pwd = $user->getPassword();
        return $passwordHasher->hashPassword($user,$pwd);
    }
}
