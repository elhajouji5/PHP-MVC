<?php

class Controller
{

    /**
     * The base class, holds the shared logic between derived controllers
     */

    public function __construct()
    {
        // Default response of controllers is of type applicatio/json
        // It could be overriden from the derived controller itself
        header('Content-Type: application/json');
        header('Accept: application/json');
    }

    /**
     * @return void
     * Set the application response message and status that will be sent to the end user
     */
    protected function response($message, $status_code = 200)
    {
        App::get_instance()->set_response([
            'status_code' => $status_code,
            'message' => $message,
        ]);
    }
}
