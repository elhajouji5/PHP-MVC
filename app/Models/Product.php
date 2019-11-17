<?php

class Product extends Model
{

    ## Products model represents the products table which holds the vehicules..
    public $table = 'products';

    public function __construct()
    {
        parent::__construct();
    }


    public static function get_all()
    {
        $data = [
            'data' => [
                [
                    'uid' => uniqid(),
                    'name' => str_shuffle('abcdefghijklmnopqrstuvwxyz'),
                    'price' => 1000,
                ]
            ]
        ];
        return $data;
    }


}
