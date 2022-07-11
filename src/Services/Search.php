<?php

namespace App\Services;

use App\Entity\Category;
use App\Entity\User;

class Search
{
    /**
     * @var string
     */
    public $name;
    /**
     * @var Category[]
     */
    public $category=[];

    /**
     * @var User
     */
    public $seller;
    // public $location=[];
}