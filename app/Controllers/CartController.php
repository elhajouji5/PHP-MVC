<?php

class CartController extends Controller
{
    /**
     * Validate cart requests
     * Prepare cart response
     */

     public function __construct()
    {
        require_once __DIR__ . "/../Models/Cart.php";
        parent::__construct();
    }

    public function get_cart($params)
    {
        $id = $params[0];
        return Cart::get($id);
    }

    public function get_cart_page()
    {
        return View::get('Cart');
    }

    /**
     * Validate and process the request
     */
    public function update_cart()
    {
        $inputs = $_POST;
        $app = App::get_instance();
        // input should be of the type array
        // and should provide the qty, product_uid and variant_uid
        if(!is_array($inputs) || !$this->validate($inputs, ['qty', 'product_uid', 'variant_uid']))
        {
            $app->set_response([
                'status_code' => '422',
                'message' => 'Invalid parameters.',
                ]);
            return;
        }
        $product = Product::with_variants(['uid', $inputs['product_uid']]);
        // Make sure the product_uid exists in the database and get the data to store it with cart
        if(!$product = count($product) ? $product[0] : null)
        {
            $app->set_response([
                'status_code' => '404',
                'message' => 'The given product deos not exist.',
                ]);
            return;
        }
        // Make sure the variant_uid exists in the database and get the price to store it with cart data
        $variant = [];
        try {
            $variant = array_filter($product['variants'], function($variant) use($inputs) {
                return $inputs['variant_uid'] == $variant['uid'];
            });
        } catch (Exception $e) { /* Skip the exception since it will be handled right bellow */}
        if(!count($variant))
        {
            $app->set_response([
                'status_code' => '404',
                'message' => 'The given variant deos not exist.',
                ]);
            return;
        }
        // Validate the qty availability
        if((int) $inputs['qty'] > (int)array_values($variant)[0]['qty'])
        {
            $app->set_response([
                'status_code' => '422',
                'message' => 'The requested quantity is unavailable.',
                ]);
            return;
        }
        $inputs['identifier'] = array_key_exists('identifier', $inputs) && strlen(trim($inputs['identifier'])) ? $inputs['identifier'] : uniqid();
        $inputs['name'] = $product['display_name'];
        try {
            // Get product image if exists
            $inputs['image'] = $product['images'][0]['image_link'];
        } catch (Exception $e) {}
        $inputs['price'] = array_values($variant)[0]['price'];
        $inputs['slug'] = $product['slug'];
        return Cart::update($inputs);
    }

    /**
     * Validate and process the request
     */
    public function delete_item($params)
    {
        $inputs = $_POST;
        // Make sure the posted data is valid
        if(!is_array($inputs) || !$this->validate($inputs, ['product_uid', 'identifier']))
        {
            $app->set_response([
                'status_code' => '422',
                'message' => 'Invalid parameters.',
            ]);
            return false;
        }
        return Cart::remove_item($inputs['identifier'], $inputs['product_uid']);
    }

    /**
     * @return boolean
     * validate request data
     * @param array $request_data
     * @param array $required_fields
     */
    public function validate(array $request_params, $required_fields)
    {
        $missing_fields = array_diff($required_fields, array_keys($request_params));
        return !count($missing_fields);
    }
}
