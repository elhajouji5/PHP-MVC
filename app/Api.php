<?php



/**
 * 
 * Register all of the routes provided by our API
 * 
 * 
 */

## Cart routes
Router::get('products', 'ProductController@index'); // Get products
Router::get('cart', 'CartController@get_cart'); // Get cart content
Router::post('cart', 'CartController@update_cart'); // Add/Edit cart items
Router::post('cart/{row_id}/delete', 'CartController@delete_item'); // Add/Edit cart items


