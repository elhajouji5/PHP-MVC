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

    public function show($params)
    {
        $uid = $params[0];
        $products = Product::with_variants(['slug', $uid]);
        return count($products) ? $products[0] : [];
    }
}
