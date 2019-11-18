<?php

class ProductController extends Controller
{
    /**
     * Validate cart requests
     * Prepare cart response
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $products = Product::all();
        return $products;
    }

    public function show($params)
    {
        $uid = $params[0];
        $products = Product::with_variants(['slug', $uid]);
        return count($products) ? $products[0] : [];
    }

    public function show_page($params)
    {
        return View::get('Show');
    }
}
