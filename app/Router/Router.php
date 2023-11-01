<?php

namespace App\Router;

class Router
{
    private static $routes = [];

    public function resolve()
    {
        include_once 'routes.php';
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        $fullPath = $_SERVER['REQUEST_URI'] ?? '/';
        $path = (explode('/paytabs-dev', $fullPath)[1]);
        $pos = strpos($path,'/', 0);
        $endPoint = substr($path, $pos);
        
        if(array_key_exists($endPoint, self::$routes[$method])){
            $callback = self::$routes[$method][$endPoint];            
            // return var_dump($callback);
            if(is_callable($callback)){
                return call_user_func($callback);
            }

            $controllerObject = new $callback[0]();
            $controllerMethod = $callback[1];
            return $controllerObject->$controllerMethod();
        }else{
            echo 'Error 404 Not Found URL';
        }
    }

    public static function get($uri, $callback)
    {
        self::$routes['get'][$uri] = $callback;
    }

    public static function post($uri, $callback)
    {
        self::$routes['post'][$uri] = $callback;
    }
    

}