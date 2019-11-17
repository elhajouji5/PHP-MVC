<?php

class View
{

    /**
     * @return void
     * Compile html page
     * @param string $name of an html page placed under Views directory
     */
    public static function get($name)
    {
        if(!is_file(__DIR__ . "/../Views/{$name}.html"))
        {
            throw new Exception("The view {$name} does not exist");
        }
        // $app = App::get_instance();
        // $app->set_response();
        header("Content-Type: text/html; charset=utf-8");
        readfile(__DIR__ . "/../Views/{$name}.html");
    }
}
