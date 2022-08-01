<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\OrderType;
use App\Services\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use Doctrine\ORM\EntityManagerInterface;

class OrderValidateController extends AbstractController
{
    #[Route('/commande/merci/{reference}', name: 'app_payment_success', methods:['GET'])]
    public function success(Cart $cart, $reference, EntityManagerInterface $entityManager): Response
    {   
        $orderReference = explode("_u",$reference)[0];

        // dd(["success",$orderReference]);
        $order = $entityManager->getRepository(Order::class)->findOneBy(['reference'=>$orderReference]);

        if(!$order || $order->getOwner() != $this->getUser() || $order->getStripeCheckoutId() == NULL){
            return $this->redirectToRoute('default_index');
        }

        if(!$order->getIsPaid()){
            $order->setIsPaid(1);
            $entityManager->flush();
            // Send mail to user.
        }
        $cart->remove();
        
        return $this->render('front/order/success.html.twig',[
            'datas' => $order,
        ]
        );
    }

    #[Route('commande/echec/{reference}', name: 'app_payment_error', methods:['GET'])]
    public function error($reference, EntityManagerInterface $entityManager): Response
    {   
        $orderReference = explode("_u",$reference)[0];

        // dd(["echec",$orderReference]);
        $order = $entityManager->getRepository(Order::class)->findOneBy(['reference'=>$orderReference]);
        if(!$order || $order->getOwner() != $this->getUser() || $order->getStripeCheckoutId() == NULL){
            return $this->redirectToRoute('default_index');
        }
        
        return $this->render('front/order/fail.html.twig',[
            'datas' => $order,
        ]
        );
    }

    #[Route('/mon-compte/mes-commandes', name: 'app_account_order', methods:['GET'])]
    public function ordersValidate(EntityManagerInterface $entityManager): Response
    {
        return $this->render('front/account/orders.html.twig',[
            'datas' => $entityManager->getRepository(Order::class)->findByOrderValidate($this->getUser()),
        ]);
    }

    #[Route('/mon-compte/mes-commandes/{id}', name: 'app_account_order_show', methods:['GET'])]
    public function orderValidate($id, EntityManagerInterface $entityManager): Response
    {
        $order = $entityManager->getRepository(Order::class)->findOneByReference($id);
        if(!$order || $order->getOwner() != $this->getUser()){
            return $this->redirectToRoute('default_index');
        }
        return $this->render('front/order/showOrder.html.twig',[
            'datas' => $order,
        ]);
    }

}
