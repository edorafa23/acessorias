<?php

namespace acessorias\System;

use Exception;

class Router
{
    public static function dispatch()
    {
        // main route values
        $httpverb = $_SERVER['REQUEST_METHOD'];
        
        // controller e method padrao
        $controller = 'main';
        $method = 'index';

        // verifica parametros de uri
        if(isset($_GET['ct'])){
            $controller = $_GET['ct'];
        }

        if(isset($_GET['mt'])){
            $method = $_GET['mt'];
        }

        // parametros do metodo
        $parameters = $_GET;

        // remove controller dos parametros
        if(key_exists("ct", $parameters)) {
            unset($parameters["ct"]);
        }

        // remove method dos parametros
        if(key_exists("mt", $parameters)) {
            unset($parameters["mt"]);
        }

        // tenta instanciar o controller e executa o método
        try {
            $class = "acessorias\Controllers\\$controller";
            $controller = new $class();
            $controller->$method(...$parameters);
        } catch (Exception $err) {
            die($err->getMessage());
        }
    }
}