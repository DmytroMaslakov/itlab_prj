<?php

namespace App\Core;


use App\Core\Attributes\Route;
use ReflectionClass;

class FrontController
{
    public function run()
    {
        $url = $_SERVER['REQUEST_URI'];
        $urlElements = explode('/', $url);

        $urlElements = array_slice($urlElements, 2);

        if (!empty($urlElements) && !empty($urlElements[0])) {

            $controller = "App\\Controllers\\".ucfirst($urlElements[0]) . "Controller";
            $method = !empty($urlElements[1]) ? $urlElements[1] : 'index';
        } else {
            $controller = 'App\Controllers\SiteController';
            $method = 'index';
        }
        if (class_exists($controller)) {
            $controller_obj = new $controller();

            $routes = [];

            $reflectionClass = new ReflectionClass($controller_obj);
            $methodList = $reflectionClass->getMethods();

            foreach ($methodList as $reflectionMethod) {
                $attributes = $reflectionMethod->getAttributes(Route::class);
                foreach ($attributes as $attribute) {
                    if ($attribute->getName() === Route::class) {
                        /** @var Route $route */
                        $route = $attribute->newInstance();
                        $routes[$route->getPath()] = ['action' => $reflectionMethod->getName(),
                            'method' => $route->getMethod()];
                    }
                }
            }

            if (!empty($routes[$method]))
                $method = $routes[$method]['action'];

            if (method_exists($controller_obj, $method)) {
                /** @var  $response Response */
                $response = $controller_obj->$method();
                if ($response instanceof Response)
                    echo $response->getTitle();
                /** @var $response string */
                if(gettype($response) === 'string' )
                    echo $response;
            } else
                echo "Error 404!";
        } else {
            echo "Error 404!";
        }
    }
}