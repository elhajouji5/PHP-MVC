<?php

class Product extends Model
{

    ## Product model represents the products table which holds the vehicules..
    protected $guarded = ['deleted_at', 'created_at'];

    /**
     * @return array
     * Fetch products from database
     */
    public static function get($uid)
    {
        $connection = Connection::get_instance()->connect_to_db();;
        if(!$connection) return;
        $self = (new static);
        $schema_name = $self->get_schema_name();
        // JOIN images AS img ON products.uid = img.imageable_id
        $data = DB::select("SELECT products.*, (select image_link from images where images.imageable_id = products.uid limit 1) AS 'image' FROM {$schema_name} ", ['uid', $uid]);
        $product = $self->remove_guarded_data($data);
        return count($product) ? $product[0] : $product;
    }

    /**
     * @return array
     * Fetch products from database
     */
    public static function all()
    {
        $connection = Connection::get_instance()->connect_to_db();;
        if(!$connection) return;
        $self = (new static);
        $schema_name = $self->get_schema_name();
        // JOIN images AS img ON products.uid = img.imageable_id
        $data = DB::select("SELECT products.*, (select image_link from images where images.imageable_id = products.uid limit 1) AS 'image' FROM {$schema_name} ");
        return $self->remove_guarded_data($data);
    }


    /**
     * @return array
     * Fetch products with their variants from database
     */
    public static function with_variants($param)
    {
        $products = DB::select("SELECT p.*, v.display_name AS variant_display_name,
            v.slug AS variant_slug, v.uid AS variant_uid, v.price AS variant_price,
            v.qty AS variant_qty
            FROM products AS p
            JOIN variants AS v ON p.uid = v.product_uid
            WHERE p.{$param[0]} = :search
        ", $param);
        $self = (new static);
        $data = $self->prepare_response($products);
        if(is_array($data) && count($data))
        {
            $images = DB::select("SELECT image_link, sort_index FROM images where imageable_id = '" . $data[0]['uid'] . "' AND imageable_type = 'product'");
            $data[0]['images'] = $images;
        }
        return $self->remove_guarded_data($data);
    }


    /**
     * @return array
     */
    private function prepare_response($products)
    {
        $response = [];
        array_walk($products, function(&$product) use(&$response){
            $variant = [];
            foreach($product as $key => $val)
            {
                // Collect the product's variants in an array and append it to the parent product
                if(preg_match('/^variant/', $key))
                {
                    unset($product[$key]);
                    $variant[str_replace('variant_', '', $key)] = $val;
                }
            }
            // if the product exists in the response append the variants to it,
            // else append the product with its variant to the response
            $index = array_search($product['uid'], array_column($response, 'uid'));
            if(is_numeric($index))
            {
                $response[$index]['variants'][] = $variant;
            } else {
                $product['variants'][] = $variant;
                $response[] = $product;
            }
        });
        return $response;
    }

    /**
     * @return array
     * @param array $data
     * Remove guarded fields from response
     */
    public function remove_guarded_data(array $data)
    {
        array_walk($data, function(&$item) {
            foreach($item as $key => $val)
            {
                if(in_array($key, $this->guarded))
                {
                    unset($item[$key]);
                }
            }
        });
        return $data;
    }
}
