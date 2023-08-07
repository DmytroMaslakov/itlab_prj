<?php

class FrontController{
    public function run()
    {
        $url = $_SERVER['REQUEST_URI'];
        $urlElements = explode('/', $url);

        $urlElements = array_slice($urlElements, 2);

        if(!empty($urlElements) && !empty($urlElements[0])) {

            $controller = ucfirst($urlElements[0]) . "Controller";
            $method = !empty($urlElements[1] ) ? $urlElements[1] : 'index';
        }else{
            $controller = 'SiteController';
            $method = 'index';
        }
        if(class_exists($controller)) {
            $controller_obj = new $controller();
            if(method_exists($controller_obj, $method)) {
                /** @var  $response Response */
                $response = $controller_obj->$method();
                if($response instanceof Response)
                echo $response->getTitle();
            }
            else
                echo "Error 404!";
        }else{
            echo "Error 404!";
        }
    }
}