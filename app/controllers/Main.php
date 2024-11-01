<?php

namespace acessorias\Controllers;

use acessorias\Controllers\BaseController;
use acessorias\Models\Agents;

class Main extends BaseController
{
    public function index()
    {

        // verifica se não há usuário ativo na sessão
        if(!check_session())
        {
            $this->login_form();
            return;
        }

        $data['user'] = $_SESSION['user'];

        $this->view('layouts/html_header');
        $this->view('navbar', $data);
        $this->view('homepage', $data);
        $this->view('footer');
        $this->view('layouts/html_footer');
    }

    public function login_form()
    {
        // verifica se usuário esta ativo na sessão
        if(check_session())
        {
            $this->index();
            return;
        }

        // verifica se há erros (depois do  login_submit)
        $data = [];
        if(!empty($_SESSION['validation_errors']))
        {
            $data['validation_errors'] = $_SESSION['validation_errors'];
            unset($_SESSION['validation_errors']);
        }

        // verifica se ha erros (depois do login)
        if(!empty($_SESSION['server_error'])){
            $data['server_error'] = $_SESSION['server_error'];
            unset($_SESSION['server_error']);
        }

        // exibir login form
        $this->view('layouts/html_header');
        $this->view('login_form', $data);
        $this->view('layouts/html_footer');
    }

    // =======================================================
    public function login_submit()
    {
        // check if there is already an active session
        if(check_session()){
            $this->index();
            return;
        }

        // check if there was a post request
        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            $this->index();
            return;
        }

        // form validation
        $validation_errors = [];
        if(empty($_POST['text_username']) || empty($_POST['text_password'])){
            $validation_errors[] = "Username e password são obrigatórios.";
        }

        // get form data
        $username = $_POST['text_username'];
        $password = $_POST['text_password'];

        // check if username is valid email and between 5 and 50 chars
        if(!filter_var($username, FILTER_VALIDATE_EMAIL))
        {
            $validation_errors[] = 'O username tem que ser um email válido.';
        }

        // check if username is between 5 and 50 chars
        if(strlen($username) < 5 || strlen($username) > 50){
            $validation_errors[] = 'O username deve ter entre 5 e 50 caracteres.';
        }

        // check if password is valid
        if(strlen($password) < 6 || strlen($password) > 12){
            $validation_errors[] = 'A password deve ter entre 6 e 12 caracteres.';
        }

        // verifica se há erros de validação
        if(!empty($validation_errors)){
            $_SESSION['validation_errors'] = $validation_errors;
            $this->login_form();
            return;
        }

        $model = new Agents();
        $result = $model->check_login($username, $password);
        if(!$result['status']){

            // logger
            logger("$username - login inválido", 'error');

            // login inválido
            $_SESSION['server_error'] = 'Login inválido';
            $this->login_form();
            return;
        }

        // logger
        logger("$username - login com sucesso", 'error');

        // carrega informações do usuário na sessão
        $results = $model->get_user_data($username);

        // adiciona usuário na sessão
        $_SESSION['user'] = $results['data'];

        // atualizar último login
        $results = $model->set_user_last_login($_SESSION['user']->id);

        // ir para página principal
        $this->index();
    }

    public function logout()
    {

        // logger
        logger($_SESSION['user']->name . ' - fez logout');

        // limpa usuario da session
        unset($_SESSION['user']);

        // vai para a index(login form)
        $this->index();
    }
}