<?php

class Controller
{

    public function __construct()
    {
        // Set the response type to applicatio/json
        header('Content-Type: application/json');
        header('Accept: application/json');
    }
    
}
