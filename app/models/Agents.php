<?php

namespace acessorias\Models;
use acessorias\Models\BaseModel;

class Agents extends BaseModel
{
    public function check_login($username, $password)
    {
        // verifica se login é valido
        $params = [
            ':username' => $username
        ];

        // verifica se há usuário no banco de dados
        $this->db_connect();
        $results = $this->query(
            "SELECT id, passwrd FROM agents " .
            "WHERE AES_ENCRYPT(:username, '" .MYSQL_AES_KEY."') = name"
        , $params);
        
        // se não houver usuário retorna false
        if($results->affected_rows == 0){
            return [
                'status' => false
            ];
        }

        // há um usuário com esse (username)
        // verifica se senha está correta
        if(!password_verify($password, $results->results[0]->passwrd)){
            return [
                'status' => false
            ];
        }

        // login esta ok
        return [
            'status' => true
        ];
    }

    public function get_user_data($username)
    {
        // traz todos os dados do usuário para inserir na sessão
        $params = [
            ':username' => $username
        ];
        $this->db_connect();
        $results = $this->query(
            "SELECT " .
                "id, " .
                "AES_DECRYPT(name, '" . MYSQL_AES_KEY . "') name, " .
                "profile " .
                "FROM agents " .
                "WHERE AES_ENCRYPT(:username, '" . MYSQL_AES_KEY . "') = name",
            $params
        );

        return [
            'status' => 'success',
            'data' => $results->results[0]
        ];
    }

    public function set_user_last_login($id)
    {
        // atualiza ultimo login do usuário
        $params = [
            ':id' => $id
        ];
        $this->db_connect();
        $results = $this->non_query(
            "UPDATE agents SET " . 
            "last_login = NOW() " . 
            "WHERE id = :id"
        ,$params);
        return $results;
    }

    public function get_agent_clients($id_agent)
    {
        // traz os usuários de acordo com o agente
        $params = [
            ':id_agent' => $id_agent
        ];
        $this->db_connect();
        $results = $this->query(
            "SELECT " .
                "id, " .
                "AES_DECRYPT(name, '" . MYSQL_AES_KEY . "') name, " .
                "gender, " .
                "birthdate, " .
                "AES_DECRYPT(email, '" . MYSQL_AES_KEY . "') email, " .
                "AES_DECRYPT(phone, '" . MYSQL_AES_KEY . "') phone, " .
                "interests, " .
                "created_at, " .
                "updated_at " .
                "FROM persons " .
                "WHERE id_agent = :id_agent " .
                "AND deleted_at IS NULL",
            $params
        );

        return [
            'status' => 'success',
            'data' => $results->results
        ];
    }

    public function check_if_client_exists($post_data)
    {
        // verifica se há um usuário com o mesmo nome
        $params = [
            ':id_agent' => $_SESSION['user']->id,
            ':client_name' => $post_data['text_name']
        ];

        $this->db_connect();
        $results = $this->query(
            "SELECT id FROM persons " . 
            "WHERE AES_ENCRYPT(:client_name, '" . MYSQL_AES_KEY . "') = name " . 
            "AND id_agent = :id_agent",
            $params
        );

        if($results->affected_rows == 0){
            return [
                'status' => false
            ];
        } else {
            return [
                'status' => true
            ];
        }
    }

    public function add_new_client_to_database($post_data)
    {
        // adiciona um novo cliente ao banco de dados

        $birthdate = new \DateTime($post_data['text_birthdate']);

        $params = [
            ':name' => $post_data['text_name'],
            ':gender' => $post_data['radio_gender'],
            ':birthdate' => $birthdate->format('Y-m-d H:i:s'),
            ':email' => $post_data['text_email'],
            ':phone' => $post_data['text_phone'],
            ':interests' => $post_data['text_interests'],
            ':id_agent' => $_SESSION['user']->id
        ];

        $this->db_connect();
        $this->non_query(
            "INSERT INTO persons VALUES(" .
                "0, " .
                "AES_ENCRYPT(:name, '" . MYSQL_AES_KEY . "'), " .
                ":gender, " .
                ":birthdate, " .
                "AES_ENCRYPT(:email, '" . MYSQL_AES_KEY . "'), " .
                "AES_ENCRYPT(:phone, '" . MYSQL_AES_KEY . "'), " .
                ":interests, " .
                ":id_agent, " .
                "NOW(), " .
                "NOW(), " .
                "NULL" .
                ")",
            $params
        );
    }

    public function get_client_data($id_client)
    {

        // traz os dados do cliente por id
        $params = [
            ':id_client' => $id_client
        ];

        $this->db_connect();
        $results = $this->query(
            "SELECT " . 
            "id, " . 
            "AES_DECRYPT(name, '" . MYSQL_AES_KEY . "') name, " . 
            "gender, " . 
            "birthdate, " . 
            "AES_DECRYPT(email, '" . MYSQL_AES_KEY . "') email, " . 
            "AES_DECRYPT(phone, '" . MYSQL_AES_KEY . "') phone, " . 
            "interests " . 
            "FROM persons " . 
            "WHERE id = :id_client"
        , $params);

        if($results->affected_rows == 0){
            return [
                'status' => 'error'
            ];
        }

        return [
            'status' => 'success',
            'data' => $results->results[0]
        ];
    }

    public function check_other_client_with_same_name($id, $name)
    {
        // verifica se há outro cliente com o mesmo nome
        $params = [
            ':id' => $id,
            ':name' => $name,
            ':id_agent' => $_SESSION['user']->id
        ];
        $this->db_connect();
        $results = $this->query(
            "SELECT id " .
                "FROM persons " .
                "WHERE id <> :id " .
                "AND id_agent = :id_agent " .
                "AND AES_ENCRYPT(:name, '" . MYSQL_AES_KEY . "') = name",
            $params
        );

        if ($results->affected_rows != 0) {
            return ['status' => true];
        } else {
            return ['status' => false];
        }
    }

    public function update_client_data($id, $post_data)
    {
        // atualiza os dados do cliente no banco de dados
        $birthdate = new \DateTime($post_data['text_birthdate']);
        $params = [
            ':id' => $id,
            ':name' => $post_data['text_name'],
            ':gender' => $post_data['radio_gender'],
            ':birthdate' => $birthdate->format('Y-m-d H:i:s'),
            ':email' => $post_data['text_email'],
            ':phone' => $post_data['text_phone'],
            ':interests' => $post_data['text_interests'],
        ];
        $this->db_connect();
        $this->non_query(
            "UPDATE persons SET " . 
            "name = AES_ENCRYPT(:name, '" . MYSQL_AES_KEY . "'), " .
            "gender = :gender, " . 
            "birthdate = :birthdate, " . 
            "email = AES_ENCRYPT(:email, '" . MYSQL_AES_KEY . "'), " .
            "phone = AES_ENCRYPT(:phone, '" . MYSQL_AES_KEY . "'), " .
            "interests = :interests, " . 
            "updated_at = NOW() " . 
            "WHERE id = :id"
        , $params);
    }

    public function delete_client($id_client)
    {
        // deleta cliente do banco de dados (hard delete)
        $params = [
            ':id' => $id_client
        ];
        $this->db_connect();
        $this->non_query("DELETE FROM persons WHERE id = :id", $params);
    }
}