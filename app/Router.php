<?php

class Router
{

    /**
     * @var array $all_routes
     */
    private static $all_routes;

    public function __construct()
    {
        static::$all_routes = static::$all_routes ?? [];
        require_once __DIR__.'/Api.php';
    }



    /**
     * @return void
     */
    public function dispatch()
    {
        if(!$requested_resource = $this->validate_route_existence())
        {
            return;
        }
        $controller_name = explode('@', $requested_resource)[0];
        $method = explode('@', $requested_resource)[1];
        require_once __DIR__ . "/Controllers/{$controller_name}.php";
        $controller = new $controller_name; // Initialize the corresponding controller
        $response = $controller->{$method}(); // Call the corresponding method
        /**
         * Make sure that the request was valid and executed successfully
         * before setting the response data
         */
        $app = App::get_instance();
        if(array_key_exists('status_code', $app->response) && (int)$app->response['status_code'] >= 400)
        {
            return;
        }
        // Set the final response data
        App::set_response($response);
    }

    /**
     * @return mixed
     * If the requested endpoint isn't registered, set a descriptif response code and status
     * else return the corresponding controller@action
     */
    private function validate_route_existence()
    {
        $routes = array_key_exists($this->get_method(), $this->get_routes()) ? $this->get_routes()[$this->get_method()] : [];
        $requested_resource = array_filter($routes, function($item){
            // Check if the requested path exists in the routes keys
            return array_key_exists($this->get_path(), $item);
        });
        if(!$requested_resource)
        {
            App::set_response([
                'status_code' => 404,
                'message' => 'The requested endpoint does not exist',
                'method' => strtoupper($this->get_method()),
                'endpoint' => $this->get_path(),
            ]);
            return;
        }
        return array_values($requested_resource)[0][$this->get_path()];
    }


    /**
     * @return string
     */
    public function get_method() : string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * @return string
     * return the requested endpoint
     */
    public function get_path() : string
    {
        // remove the slash(/) from the beggining if exists
        return preg_replace("/(^\/)/", "", $_SERVER['REQUEST_URI']);
    }

    /**
     * @return array
     */
    public function get_routes() : array
    {
        return static::$all_routes;
    }

    /**
     * @return void
     */
    public static function get($path, $destination)
    {
        static::$all_routes['get'][] = [$path => $destination];
    }

    /**
     * @return void
     */
    public static function post($path, $destination)
    {
        static::$all_routes['post'][] = [$path => $destination];
    }

}
