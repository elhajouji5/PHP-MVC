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

    public function show($slug)
    {
        if(!$slug)
        {
            return $this->response('The slug is required.', 422);
        }
        $products = Product::with_variants(['slug', $slug]);
        if(!$products)
        {
            return $this->response('The product your looking for does not exist.', 404);
        }
        return count($products) ? $products[0] : [];
    }

    public function show_page($params)
    {
        return View::get('Show');
    }
}
