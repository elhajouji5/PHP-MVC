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
     */
    public static function update($request_data)
    {
        $identifier = $request_data['identifier'];
        $content = json_encode([
            'variant_uid' => $request_data['variant_uid'],
            'product_uid' => $request_data['product_uid'],
            'qty' => $request_data['qty'],
        ]);
        $current_time = date('Y-m-d H:i:s');
        DB::statement("DELETE from carts where identifier = '{$identifier}'");
        DB::statement("INSERT INTO carts VALUES ('{$identifier}', '{$content}', 'default', '{$current_time}' )");
        return ['status' => 'success', 'identifier' => $identifier];
    }

}
