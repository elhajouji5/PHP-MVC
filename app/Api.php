<?php



/**
 * 
 * Register all of the routes provided by our API
 * 
 * 
 */

###### Views routes ######
# Homepage
Router::get('/', 'HomeController@index'); // return homepage
# Product single page
Router::get('/products/{uid}', 'ProductController@show_page'); // return product single page



###### Api routes ######
# Products routes
Router::get('/api/products', 'ProductController@index'); // Get products
Router::get('/api/products/{uid}', 'ProductController@show'); // Get a given product

# Cart routes
Router::get('/api/cart/{identifier}', 'CartController@get_cart'); // Get cart content
Router::post('/api/cart', 'CartController@update_cart'); // Add/Edit cart items
Router::post('/api/cart/{row_id}/delete', 'CartController@delete_item'); // Add/Edit cart items


