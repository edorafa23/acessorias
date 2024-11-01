<?php

namespace acessorias\Models;
use acessorias\System\Database;

abstract class BaseModel
{
    public $db;

    public function db_connect()
    {
        $options = [
            'host' => MYSQL_HOST,
            'database' => MYSQL_DATABASE,
            'username' => MYSQL_USERNAME,
            'password' => MYSQL_PASSWORD,
        ];

        $this->db = new Database($options);
    }

    // ler informação
    public function query($sql = "", $params = [])
    {
        return $this->db->execute_query($sql, $params);
    }

    // atualizar ou inserir informação
    public function non_query($sql = "", $params = [])
    {
        return $this->db->execute_non_query($sql, $params);
    }
}