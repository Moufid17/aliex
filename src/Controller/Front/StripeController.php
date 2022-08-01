<?php

namespace App\Controller\Front;

use App\Services\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Entity\OrderDetails;
use App\Entity\Order;
use App\Entity\Product;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripeController extends AbstractController
{
    #[Route('/mon-compte/commande/create-checkout-session/{reference}', name: 'checkout' , methods:['GET'])]
    public function index($reference, EntityManagerInterface $entityManager):Response
    {
        $order = $entityManager->getRepository(Order::class)->findOneBy(['reference'=>$reference]);
        if(!$order){
            return $this->redirectToRoute('app_order', [], Response::HTTP_SEE_OTHER);
        }
        $YOUR_DOMAIN = 'http://localhost:8080';
        $productLine = [];
        // Card content
        foreach($order->getOrderDetails()->getValues() as $product){     
            $product_object = $entityManager->getRepository(Product::class)->findOneBy(['name'=>$product->getProduct()]);       
            $productLine[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $product->getProduct(),
                        'images' => [$YOUR_DOMAIN."/images/products/".$product_object->getImageName()]
                    ],
                    'unit_amount' => $product->getPrice(),
                ],
                'quantity' => $product->getQte(),
            ];
        }

        // Carrier content
        $productLine[] = [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => $order->getCarrierName(),
                    'images' => [$YOUR_DOMAIN."/images/products/".$product_object->getImageName()]
                ],
                'unit_amount' => $order->getCarrierPrice()*100,
            ],
            'quantity' => 1,
        ];
        

        $ref_url = $order->getReference()."_u".uniqid();

        Stripe::setApiKey($this->getParameter('app.stripe_api_key'));
        $checkout_session = Session::create([
            'line_items' => [$productLine],
            'customer_email' => $this->getUser()->getEmail(),
            'mode' => 'payment',
            'success_url' => $this->generateUrl('app_payment_success', ['reference'=>$ref_url], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('app_payment_error', ['reference'=>$ref_url], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        $order->setStripeCheckoutId($checkout_session->id);
        $entityManager->flush();

        return $this->redirect($checkout_session->url,Response::HTTP_SEE_OTHER);
    }

}
