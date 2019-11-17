<?php

class CartController extends Controller
{
    /**
     * Validate cart requests
     * Prepare cart response
     */

    public function get_cart()
    {
        return [
            'cart_items' => [
                [
                    'method' => 'post',
                    'row_id' => uniqid(),
                    'name' => str_shuffle('abcdefghijklmnopqrstuvwxyz'),
                    'price' => 1000,
                ]
            ]
        ];
    }
   


    public function update_cart()
    {
        return 'CartController@update_cart';
    }
   

    public function delete_item()
    {
        return 'CartController@delete_item';
    }
   
}
