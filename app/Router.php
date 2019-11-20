<?php

class Router
{

    /**
     * @var array $all_routes
     */
    private static $all_routes;

    /**
     * @var array $params
     * this variable holds the requests params
     */
    private $params;

    public function __construct()
    {
        static::$all_routes = static::$all_routes ?? [];
        require_once __DIR__.'/Api.php';
        $this->params = [];
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
        $controller_name = explode('@', array_values($requested_resource)[0])[0];
        $method = explode('@', array_values($requested_resource)[0])[1];
        require_once __DIR__ . "/Controllers/{$controller_name}.php"; // Load the corresponding controller
        $controller = new $controller_name; // Instantaite the corresponding controller
        $response = $controller->{$method}(...array_values($this->params ?? [])); // Call the corresponding method
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
        $requested_resource = array_filter($routes, function($registered_route){
            // Ith the requested endpoint exists in the registered routes, return it
            if(array_key_exists($this->get_path(), $registered_route))
            {
                return true;
            }
            // Else check if any of the registered dynamic route matches the requested endpoint
            return $this->check_dynamic_routes(array_keys($registered_route)[0]);
        });
        if(!$requested_resource)
        {
            // Set the response type to applicatio/json
            header('Content-Type: application/json');
            header('Accept: application/json');
            App::set_response([
                'status_code' => 404,
                'message' => 'The requested endpoint does not exist',
                'method' => strtoupper($this->get_method()),
                'endpoint' => $this->get_path(),
            ]);
            return;
        }
        return array_values($requested_resource)[0];
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
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * @return array
     */
    public function get_routes() : array
    {
        return static::$all_routes;
    }


    /**
     * @return boolean
     * Search for a dynamic route matching the requested one
     * example: if the requested route is "products/PR-363545363",
     * we return the route "products/{id}" if exists
     */
    public function check_dynamic_routes($registered_dynamic_route)
    {
        $requested_route = $this->get_path();
        $dynamic_route = $registered_dynamic_route;
        $requested_route = explode('/', $requested_route);
        $dynamic_route = explode('/', $dynamic_route);
        $requested_route = array_filter($requested_route, function($val){
            return !!$val;
        });
        $dynamic_route = array_filter($dynamic_route, function($val){
            return !!$val;
        });
        $dynamic_route = array_values($dynamic_route ?? []);
        $requested_route = array_values($requested_route ?? []);
        if(count($dynamic_route) != count($requested_route)) return;
        for($i = 0; $i < count($dynamic_route); $i++)
        {
            if($dynamic_route[$i] == $requested_route[$i]) continue;
            if(preg_match("/^\{.*\}$/", $dynamic_route[$i]))
            {
                $dynamic_route[$i] = $requested_route[$i];
                $this->params[] = $requested_route[$i];
            }
        }
        $requested_route = implode('/', $requested_route);
        $dynamic_route = implode('/', $dynamic_route);
        return ($requested_route == $dynamic_route);
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
