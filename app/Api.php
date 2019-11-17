<?php



/**
 * 
 * Register all of the routes provided by our API
 * 
 * 
 */

## Cart routes
Router::get('/', 'HomeController@index'); // Get products
Router::get('/api/products', 'ProductController@index'); // Get products
Router::get('/api/products/{uid}', 'ProductController@show'); // Get products
Router::get('/api/cart', 'CartController@get_cart'); // Get cart content
Router::post('/api/cart', 'CartController@update_cart'); // Add/Edit cart items
Router::post('/api/cart/{row_id}/delete', 'CartController@delete_item'); // Add/Edit cart items


