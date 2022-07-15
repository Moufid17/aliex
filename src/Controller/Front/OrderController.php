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

class OrderController extends AbstractController
{
    #[Route('/mon-compte/commande', name: 'app_order')]
    public function index(Cart $cart, Request $request): Response
    {
        if(!$this->getUser()->getAddresses()->getValues()){
            return $this->redirectToRoute('app_account_user_address_add');
        }

        $form = $this->createForm(OrderType::class,null,[
                'user' => $this->getUser(),
            ]
        );

        return $this->render('front/order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getAll(),
        ]);
    }

    #[Route('/mon-compte/commande/recapitulatif', name: 'app_order_recap', methods: ['POST'])]
    public function add(Cart $cart, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OrderType::class,null,[
            'user' => $this->getUser(),
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            // -------------------- Order -------------------- //
            $date = new \DateTimeImmutable();
            $carrier = $form->get('carriers')->getData();
            $address = $form->get('addresses')->getData();

            $address_content = $this->getUser()->getFirstname().' '.$this->getUser()->getLastname();
            $address_content .= '<br>'. $address->getName();
            
            if($address->getCompany()){
                $address_content .= '<br>'.$address->getStreet();
            }
            $address_content .= '<br>'.$address->getStreet().','.$address->getCity();
            $address_content .= '<br>'.$address->getCodepostal();
            $address_content .= ' - '.$address->getCountry();

            $orderRef = $date->format('Ymd').'-'.uniqid();
            // Enregister ma commande | Order
            $order = new Order();
            $order->setReference($orderRef);
            $order->setOwner($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carrier->getName());
            $order->setCarrierPrice($carrier->getPrice());
            $order->setShippingAddress($address_content);
            $order->setIsPaid(0);
            $entityManager->persist($order);

            //  ----------------- Order Details -------------  //
            // Enregister mes produits | OrderDetails
            foreach($cart->getAll() as $product){
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQte($product['qte']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['product']->getPrice() * $product['qte']);
                $entityManager->persist($orderDetails);
            }


            $entityManager->flush();
            
            return $this->render('front/order/add.html.twig', [
                'cart' => $cart->getAll(),
                'shippingMode' => $carrier,
                'address' => $address_content,
                'reference' => $order->getReference(),
            ]);
        }

        return $this->redirectToRoute('app_user_cart_index', [], Response::HTTP_SEE_OTHER);

    }


    #[Route('/payment-success/{reference}', name: 'app_payment_success', methods:['GET'])]
    public function success($reference, EntityManagerInterface $entityManager): Response
    {   
        $order = $entityManager->getRepository(Order::class)->findOneBy(['reference'=>$reference]);
        
        if(!$order || $order->getOwner() == $this->getUser()){
            return $this->redirectToRoute('default_index');
        }

        if(!$order->getIsPaid()){
            $order->setIsPaid(1);
            $entityManager->flush();

            // Send mail to user.
        }

        return $this->render('front/order/success.html.twig',
        );
    }


    #[Route('/payment-error/{reference}', name: 'app_payment_error', methods:['GET'])]
    public function error($reference, EntityManagerInterface $entityManager): Response
    {   
        $order = $entityManager->getRepository(Order::class)->findOneBy(['reference'=>$reference]);
        if(!$order || $order->getOwner() == $this->getUser()){
            return $this->redirectToRoute('default_index');
        }

        
        return $this->render('front/order/fail.html.twig',
        );
    }
}
