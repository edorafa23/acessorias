<?php

namespace acessorias\Controllers;

use acessorias\Controllers\BaseController;
use acessorias\Models\Agents;

class Agent extends BaseController
{
    // =======================================================
    public function my_clients()
    {
        if(!check_session() || $_SESSION['user']->profile != 'agent')
        {
            header('Location: index.php');
        }

        // get todos os clientes
        $id_agent = $_SESSION['user']->id;
        $model = new Agents();
        $results = $model->get_agent_clients($id_agent);

        $data['user'] = $_SESSION['user'];
        $data['clients'] = $results['data'];

        $this->view('layouts/html_header');
        $this->view('navbar', $data);
        $this->view('agent_clients', $data);
        $this->view('footer');
        $this->view('layouts/html_footer');
    }

    public function new_client_form()
    {
        if(!check_session() || $_SESSION['user']->profile != 'agent')
        {
            header('Location: index.php');
        }

        $data['user'] = $_SESSION['user'];
        $data['flatpickr'] = true;

        // verifica se há erros de validação
        if(!empty($_SESSION['validation_errors'])){
            $data['validation_errors'] = $_SESSION['validation_errors'];
            unset($_SESSION['validation_errors']);
        }

        // verifica se há erros no servidor
        if(!empty($_SESSION['server_error'])){
            $data['server_error'] = $_SESSION['server_error'];
            unset($_SESSION['server_error']);
        }

        $this->view('layouts/html_header', $data);
        $this->view('navbar', $data);
        $this->view('insert_client_form', $data);
        $this->view('footer');
        $this->view('layouts/html_footer');
    }

    public function new_client_submit()
    {
        if (!check_session() || $_SESSION['user']->profile != 'agent' || $_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: index.php');
        }

        // form validation
        $validation_errors = [];

        // text_name
        if (empty($_POST['text_name'])) {
            $validation_errors[] = "Nome é de preenchimento obrigatório.";
        } else {
            if (strlen($_POST['text_name']) < 3 || strlen($_POST['text_name']) > 50) {
                $validation_errors[] = "O nome deve ter entre 3 e 50 caracteres.";
            }
        }

        // gender
        if(empty($_POST['radio_gender'])){
            $validation_errors[] = "É obrigatório definir o gênero.";
        }

        // text_birthdate
        if(empty($_POST['text_birthdate'])){
            $validation_errors[] = "Data de nascimento é obrigatória.";
        } else {
            // verifica se data de nascimento é valida e se é anterior a data de hoje
            $birthdate = \DateTime::createFromFormat('d-m-Y', $_POST['text_birthdate']);
            if(!$birthdate) {
                $validation_errors[] = "A data de nascimento não está no formato correto.";
            } else {
                $today = new \DateTime();
                if($birthdate >= $today){
                    $validation_errors[] = "A data de nascimento deve ser anterior a data de hoje.";
                }
            }
        }

        // email
        if(empty($_POST['text_email'])){
            $validation_errors[] = "Email é de preenchimento obrigatório.";
        } else {
            if(!filter_var($_POST['text_email'], FILTER_VALIDATE_EMAIL)){
                $validation_errors[] = "Email não é válido.";
            }
        }

        // phone
        if(empty($_POST['text_phone'])){
            $validation_errors[] = "Telefone é de preenchimento obrigatório.";
        } else {
            if(!preg_match("/^9{1}\d{8}$/", $_POST['text_phone'])){
                $validation_errors[] = "O telefone deve começar por 9 e ter 9 algarismos no total.";
            }
        }

        // verifica se há erros de validação para retornar ao form
        if(!empty($validation_errors)){
            $_SESSION['validation_errors'] = $validation_errors;
            $this->new_client_form();
            return;
        }

        // verifica se já há um cliente com o mesmo nome
        $model = new Agents();
        $results = $model->check_if_client_exists($_POST);

        if($results['status']){

            // uma pessoa com o mesmo nome existe. Retorna um server error
            $_SESSION['server_error'] = "Já existe um cliente com esse nome.";
            $this->new_client_form();
            return;
        }

        // adiciona o cliente no banco de dados
        $model->add_new_client_to_database($_POST);

        // logger
        logger(get_active_user_name() . " - adicionou novo cliente: " . $_POST['text_email']);

        // retorna para a pagina de clientes
        $this->my_clients();
    }

    public function edit_client($id)
    {
        if (!check_session() || $_SESSION['user']->profile != 'agent') {
            header('Location: index.php');
        }

        // verifica e o $id é valido
        $id_client = aes_decrypt($id);
        if(!$id_client){

            // id_client é invalido
            header('Location: index.php');
        }

        // carrega o model para trazer os dados do cliente
        $model = new Agents();
        $results = $model->get_client_data($id_client);

        // verifica se há dados do cliente
        if($results['status'] == 'error'){

            // dados do usuário inválidos
            header('Location: index.php');
        }

        $data['client'] = $results['data'];
        $data['client']->birthdate = date('d-m-Y', strtotime($data['client']->birthdate));

        // exibe o edit client form
        $data['user'] = $_SESSION['user'];
        $data['flatpickr'] = true;

        // verifica se há erros de validação
        if(!empty($_SESSION['validation_errors'])){
            $data['validation_errors'] = $_SESSION['validation_errors'];
            unset($_SESSION['validation_errors']);
        }

        // verifica se há erros no servidor
        if(!empty($_SESSION['server_error'])){
            $data['server_error'] = $_SESSION['server_error'];
            unset($_SESSION['server_error']);
        }

        $this->view('layouts/html_header', $data);
        $this->view('navbar', $data);
        $this->view('edit_client_form', $data);
        $this->view('footer');
        $this->view('layouts/html_footer');
    }

    public function edit_client_submit()
    {
        if (!check_session() || $_SESSION['user']->profile != 'agent' || $_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: index.php');
        }

        // form validation
        $validation_errors = [];

        // text_name
        if (empty($_POST['text_name'])) {
            $validation_errors[] = "Nome é obrigatório.";
        } else {
            if (strlen($_POST['text_name']) < 3 || strlen($_POST['text_name']) > 50) {
                $validation_errors[] = "O nome deve ter entre 3 e 50 caracteres.";
            }
        }

        // gender
        if(empty($_POST['radio_gender'])){
            $validation_errors[] = "Gênero é obrigatório.";
        }

        // text_birthdate
        if(empty($_POST['text_birthdate'])){
            $validation_errors[] = "Data de nascimento é obrigatória.";
        } else {
            // check if birthdate is valid and is older than today
            $birthdate = \DateTime::createFromFormat('d-m-Y', $_POST['text_birthdate']);
            if(!$birthdate) {
                $validation_errors[] = "A data de nascimento não está no formato correto.";
            } else {
                $today = new \DateTime();
                if($birthdate >= $today){
                    $validation_errors[] = "A data de nascimento tem que ser anterior ao dia atual.";
                }
            }
        }

        // email
        if(empty($_POST['text_email'])){
            $validation_errors[] = "Email é obrigatório.";
        } else {
            if(!filter_var($_POST['text_email'], FILTER_VALIDATE_EMAIL)){
                $validation_errors[] = "Email não é válido.";
            }
        }

        // phone
        if(empty($_POST['text_phone'])){
            $validation_errors[] = "Telefone é obrigatório.";
        } else {
            if(!preg_match("/^9{1}\d{8}$/", $_POST['text_phone'])){
                $validation_errors[] = "O telefone deve começar por 9 e ter 9 algarismos no total.";
            }
        }

        // verifica se o id_client esta presente no POST e se é valido
        if(empty($_POST['id_client'])){
            header('Location: index.php');
        }
        $id_client = aes_decrypt($_POST['id_client']);
        if(!$id_client){
            header('Location: index.php');
        }

        // verifica se há erros de validação para retornar ao form
        if(!empty($validation_errors)){
            $_SESSION['validation_errors'] = $validation_errors;
            $this->edit_client(aes_encrypt($id_client));
            return;
        }

        // verifica se há outro cliente com o mesmo nome
        $model = new Agents();
        $results = $model->check_other_client_with_same_name($id_client, $_POST['text_name']);

        // verifica se ha erros na edição
        if($results['status']){
            $_SESSION['server_error'] = "Já existe outro cliente com o mesmo nome.";
            $this->edit_client(aes_encrypt($id_client));
            return;
        }

        // atualiza o cliente no banco de dados
        $model->update_client_data($id_client, $_POST);

        // logger
        logger(get_active_user_name() . " - atualizou dados do cliente ID: " . $id_client);

        // retorna para a pagina de clientes
        $this->my_clients();
    }

    public function delete_client($id)
    {
        if (!check_session() || $_SESSION['user']->profile != 'agent') {
            header('Location: index.php');
        }

        // verifica se $id é válido
        $id_client = aes_decrypt($id);
        if(!$id_client){
            header('Location: index.php');
        }

        // carrega o model para trazer os dados do cliente
        $model = new Agents();
        $results = $model->get_client_data($id_client);

        if(empty($results['data'])){
            header('Location: index.php');
        }

        // exibe a view
        $data['user'] = $_SESSION['user'];
        $data['client'] = $results['data'];

        $this->view('layouts/html_header');
        $this->view('navbar', $data);
        $this->view('delete_client_confirmation', $data);
        $this->view('footer');
        $this->view('layouts/html_footer');
    }

    // =======================================================
    public function delete_client_confirm($id)
    {
        if (!check_session() || $_SESSION['user']->profile != 'agent') {
            header('Location: index.php');
        }

        // verifica se $id é válido
        $id_client = aes_decrypt($id);
        if(!$id_client){
            header('Location: index.php');
        }

        // carrega o model para trazer os dados do cliente
        $model = new Agents();
        $model->delete_client($id_client);

        // logger
        logger(get_current_user() . ' - Deletado o cliente id: ' . $id_client);

        // retorna para a pagina de clientes
        $this->my_clients();
    }
}