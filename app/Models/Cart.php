<?php

class Cart extends Model
{

    ## Cart model represents the carts table which holds the vehicules..


    /**
     * return array
     */
    public static function get(string $identifier)
    {
        $cart = DB::select('SELECT content, last_updated_at, instance FROM carts WHERE identifier = :search LIMIT 1', ['identifier', $identifier]);
        if(count($cart))
        {
            $content = json_decode($cart[0]['content'], true);
        }
        return [
            'identifier' => $identifier,
            'content' => $content ?? [],
            'last_udpated_at' => $cart[0]['last_udpated_at'],
            'instance' => $cart[0]['instance'],
        ];
    }

    /**
     * @return array
     * Get old cart data
     * Find the submitted product and edit or add it
     * Persist the changes
     * Return the response back
     */
    public static function update($request_data)
    {
        $identifier = $request_data['identifier'];
        $cart = Cart::get($identifier);
        // Get old data
        $content = [];
        if(is_array($cart) && is_array($cart['content']))
        {
            $content = $cart['content'];
        }
        // Find the submitted product_uid
        $product_uid = $request_data['product_uid'];
        $content = array_filter($content, function($row) use($product_uid) {
            return $row['product_uid'] != $product_uid;
        });

        $content[] = [
                'image' => $request_data['image'],
                'price' => $request_data['price'],
                'slug' => $request_data['slug'],
                'name' => $request_data['name'],
                'variant_uid' => $request_data['variant_uid'],
                'product_uid' => $request_data['product_uid'],
                'qty' => $request_data['qty'],
        ];
        $current_time = date('Y-m-d H:i:s');
        $content = json_encode($content, JSON_UNESCAPED_UNICODE);
        DB::statement("DELETE from carts where identifier = '{$identifier}'");
        DB::statement("INSERT INTO carts VALUES ('{$identifier}', '{$content}', 'default', '{$current_time}' )");
        return ['status' => 'success', 'identifier' => $identifier];
    }


    /**
     * @return array
     * Get cart data
     * Find the submitted item_uid and delete it if exists, return false otherwise
     * Persist the changes
     * Return the response back
     */
    public static function remove_item($identifier, $item_uid)
    {
        // Fetch the cart
        $cart = Cart::get($identifier);
        // Find the submitted item_uid
        if(!is_array($cart) || !is_array($cart['content']))
        {
            return false;
        }
        // Find the submitted item
        $index = array_search($item_uid, array_column($cart['content'], 'product_uid'));
        // Check item existence
        if(!is_numeric($index))
        {
            App::get_instance()->set_response([
                'status_code' => 404,
                'message' => 'This product does not exist in your cart',
            ]);
            return;
        }
        // Delete it
        unset($cart['content'][$index]);
        // Drop the old cart record
        DB::statement("DELETE FROM carts WHERE identifier = :search", ['identifier', $identifier]);
        if(!count($cart['content']))
        {
            App::get_instance()->set_response([
                'status_code' => 200,
                'message' => 'This product has been removed from your cart',
            ]);
            return true;
        }
        // Save changes
        $content = json_encode(($cart['content']), JSON_UNESCAPED_UNICODE);
        DB::statement("INSERT INTO carts VALUES ('{$identifier}', '{$content}')");
        // Return the response
        return ['status' => 'success', 'identifier' => $identifier];
    }

}
