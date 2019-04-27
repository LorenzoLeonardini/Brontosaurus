<?php

namespace Brontosaurus;

class FormToken {

    public static function generateToken(string $name) : string {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if(!isset($_SESSION['form_tokens_'.$name])) {
            $_SESSION['form_tokens_'.$name] = [];
        }
        if(count($_SESSION['form_tokens_'.$name]) > 19) {
            array_shift($_SESSION['form_tokens_'.$name]);
        }

        $token = md5(uniqid(rand(), true));
        $_SESSION['form_tokens_'.$name][count($_SESSION['form_tokens_'.$name])] = $token;
        return $token;
    }

    public static function validateToken(string $name) : bool {
        if(!isset($_POST['form_token'])) {
            throw new FormTokenException('The server has not received any form token into the "form_token" field.');
        }

        if(!isset($_SESSION['form_tokens_'.$name]) || !in_array($_POST['form_token'], $_SESSION['form_tokens_'.$name], true)) {
            return false;
        }

        return true;
    }

}

class FormTokenException extends \Exception {}
