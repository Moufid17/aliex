<?php

namespace App\Services;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    private $session;
    public function __construct(private RequestStack $requestStack, private EntityManagerInterface $entityManager)
    {
        $this->requestStack = $requestStack;
        $this->session = $this->requestStack->getSession();
        $this->entityManager = $entityManager;
    }

    public function add($id){
        // Empty array is returned if id <cart> didn't exist in session.
        $cart = $this->session->get('cart', []);

        if(!empty($cart[$id])){
            $cart[$id]++;
        }else{
            $cart[$id] = 1;
        }

        $this->session->set('cart', $cart);
    }

    public function decrease($id){
        // Empty array is returned if id <cart> didn't exist in session.
        $cart = $this->session->get('cart', []);

        if($cart[$id] == 1){
            $cart = $this->delete($id);            
        }
        else if(!empty($cart[$id]) && $cart[$id] > 1){
            $cart[$id]--;
        }
        return $this->session->set('cart', $cart);
    }

    public function get(){
        return $this->session->get('cart', []);
    }

    public function getAll(){
        $completeCart = [];
        if($this->get() != []){
            foreach($this->get() as $id => $qte){
                $product = $this->entityManager->getRepository(Product::class)->findOneBy(['id'=>$id]);
                // Skip if user use path to add unexisting product by id.
                if (!$product){
                    $this->delete($id);
                    continue;
                }

                $completeCart[] = [
                    'product' => $product,
                    'qte' => $qte
                ];
            }
        }
        return $completeCart;
    }
    // Remove one product in your cart by id.
    public function delete($id){
        $cart = $this->session->get('cart', []);
        unset($cart[$id]);
        return $this->session->set('cart', $cart);
    }

    // Remove all products in your cart.
    public function remove(){
        $this->session->remove('cart');
    }
}