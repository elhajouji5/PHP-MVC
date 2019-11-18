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
        $inputs['identifier'] = array_key_exists('identifier', $inputs) && strlen(trim($inputs['identifier'])) ? $inputs['identifier'] : uniqid();
        // Make sure the variants_uid & product_uid exists in the database and get the product_name to persist it with cart data
        return Cart::update($inputs);
    }


    public function delete_item()
    {
        return 'CartController@delete_item';
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
