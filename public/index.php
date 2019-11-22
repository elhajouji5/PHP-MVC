<?php

/**
 * THIS IS A SIMPLE Oriented Object APP CREATED BY ABDELILAH ELHAJOUJI(github.com/elhajouji5)
 * 
 */

require_once __DIR__ . '/../app/Application.php';
/**
 * Create a new instance of the app
 */
$app = App::get_instance();

// Validate and process the request
$app->handle_request();


// Prepare and send back the response
$app->response();
