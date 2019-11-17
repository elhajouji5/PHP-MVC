<?php

class ProductController extends Controller
{
    /**
     * Validate cart requests
     * Prepare cart response
     */
    public function __construct()
    {
        require_once __DIR__ . "/../Models/Product.php";
        parent::__construct();
    }

    public function index()
    {
        $products = Product::get();
        return $products;
    }   
}
