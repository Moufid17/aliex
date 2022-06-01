<?php

namespace App\Components;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('card')]
class CardComponent
{
    public int $id;

    public function __construct(private ProductRepository $productRepository)
    {

    }

    public function getCard(): Product
    {
        return $this->productRepository->find($this->id);
    }
}