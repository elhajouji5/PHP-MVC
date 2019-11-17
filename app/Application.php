<?php
// namespace App;

class App
{

    /**
     * Application instance
     * @var App $instance
     */
    private static $instance;

    public $response;
    public $config;

    private function __construct()
    {
        $this->response = [];
        $this->load_classes();
    }

    /**
     * @return App
     * If the app is already instantiated return the that instance, otherwise create new instance and return it
     */
    public static function get_instance()
    {
        return static::$instance ?? static::$instance = new static();
    }

    // Validate and process the request
    public function handle_request()
    {
        require_once __DIR__.'/Router.php';
        $router = new Router();
        $router->dispatch();
    }

    // Prepare and send back the response
    public function response()
    {
        $this->set_status_code();
        echo json_encode($this->response);
        $this->distruct();
        exit(0);
    }

    /**
     * @return mixed
     * Set response status code
     */
    public function set_status_code()
    {
        if(is_array($this->response) && array_key_exists('status_code', $this->response))
        {
            return http_response_code($this->response['status_code']);
        }
        http_response_code(200);
    }

    public static function set_response($response)
    {
        $app = App::get_instance();
        $app->response = $response;
    }

    /**
     * @return void
     * Delete the application from server's memory
     */
    private function distruct()
    {
        $this->response = [];
    }

    /**
     * Load important classes
     */
    private function load_classes()
    {
        require_once __DIR__ . "/Models/Connection.php";
        require_once __DIR__ . "/Models/DB.php";
        require_once __DIR__ . "/Models/Model.php";
        require_once __DIR__ . "/Controllers/Controller.php";
        $this->config = include(__DIR__ . "/../config/config.php");
    }
}
