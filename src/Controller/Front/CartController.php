<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Cart;

class CartController extends AbstractController
{
    #[Route('/mon-compte/mon-panier', name: 'app_user_cart_index')]
    public function index(Cart $cart): Response
    {   
        return $this->render('front/cart/index.html.twig', [
            'datas' => $cart->getAll(),
        ]);
    }

    #[Route('/mon-compte/mon-panier/add/{id}', name: 'app_user_cart_add')]
    public function add($id, Cart $cart): Response
    {   
        $cart->add($id);
        return $this->redirectToRoute('app_user_cart_index');
    }
    
    #[Route('/mon-compte/mon-panier/decrease/{id}', name: 'app_user_cart_decrease')]
    public function decrease($id, Cart $cart): Response
    {   
        $cart->decrease($id);
        return $this->redirectToRoute('app_user_cart_index');
    }

    #[Route('/mon-compte/mon-panier/delete/{id}', name: 'app_user_cart_delete')]
    public function delete($id, Cart $cart): Response
    {   
        $cart->delete($id);
        return $this->redirectToRoute('app_user_cart_index');
    }

    #[Route('/mon-compte/mon-panier/remove', name: 'app_user_cart_remove')]
    public function remove(Cart $cart): Response
    {   
        $cart->remove();
        return $this->redirectToRoute('app_product');
    }

}
