<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

function check_session()
{
    return isset($_SESSION['user']);
}

function logger($message = '', $level = 'info')
{
    // cria canal de log
    $log = new Logger('app_logs');
    $log->pushHandler(new StreamHandler(LOGS_PATH));

    // adiciona mensagem de log
    switch ($level) {
        case 'info':
            $log->info($message);
            break;
        case 'notice':
            $log->notice($message);
            break;
        case 'warning':
            $log->warning($message);
            break;
        case 'error':
            $log->error($message);
            break;
        case 'critical':
            $log->critical($message);
            break;
        case 'alert':
            $log->alert($message);
            break;
        case 'emergency':
            $log->emergency($message);
            break;
        
        default:
            $log->info($message);
            break;
    }
}

function aes_encrypt($value)
{
    // encrypt $value
    return bin2hex(openssl_encrypt($value, 'aes-256-cbc', OPENSSL_KEY, OPENSSL_RAW_DATA, OPENSSL_IV));
}

function aes_decrypt($value)
{
    // decrypt $value
    if(strlen($value) % 2 != 0){
        return false;
    }

    return openssl_decrypt(hex2bin($value), 'aes-256-cbc', OPENSSL_KEY, OPENSSL_RAW_DATA, OPENSSL_IV);
}

function get_active_user_name()
{
    return $_SESSION['user']->name;
}

function printData($data, $die = true)
{
    echo '<pre>';
    if(is_object($data) || is_array($data)){
        print_r($data);
    } else {
        echo $data;
    }

    if($die) {
        die();
    }
}