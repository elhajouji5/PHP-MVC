<?php

class Connection
{

    private static $instance;

    private function __construct()
    {
        //    
    }

    public static function get_instance()
    {
        return static::$instance ?? static::$instance = new static;
    }

    public function connect_to_db()
    {
        $app = App::get_instance();
        $db = $app->config["DB_DATABASE"];
        $host = $app->config["DB_HOST"];
        $username = $app->config["DB_USERNAME"];
        $password = $app->config["DB_PASSWORD"];
        try {
            $connection = new PDO("mysql:host={$host};dbname={$db}", $username, $password);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $connection;
        } catch (PDOException $e) {
            // Set the error in the response
            $a = App::get_instance();
            $a->set_response(['status_code' => 500, 'message' => $e->getMessage()]);
            return;
        }
    }

    
}