<?php

namespace acessorias\Controllers;

abstract class BaseController
{
    public function view($view, $data = [])
    {
        // verifica data é um array
        if(!is_array($data)){
            die('Data is not an array:' . var_dump($data));
        }

        // transforma data em variáveis
        extract($data);

        // inclui o arquivo se ele existir
        if(file_exists("../app/views/$view.php")){
            require_once("../app/views/$view.php");
        } else {
            die("View does not exists: " . $view);
        }
    }
}
