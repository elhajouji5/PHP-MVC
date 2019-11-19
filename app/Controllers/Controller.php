<?php

class Controller
{

    /**
     * The base class, holds the shared logic between derived controllers
     */

    public function __construct()
    {
        // Set the response type to applicatio/json
        header('Content-Type: application/json');
        header('Accept: application/json');
    }
    
}
