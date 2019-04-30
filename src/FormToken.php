<?php

namespace Brontosaurus\FormToken;

function generateToken(string $name) : string {
    if(!isset($_SESSION['form_tokens_'.$name])) {
        $_SESSION['form_tokens_'.$name] = [];
    }
    if(count($_SESSION['form_tokens_'.$name]) > \Brontosaurus\Config::getProperty("form_token", "maximum_tokens") - 1) {
        array_shift($_SESSION['form_tokens_'.$name]);
    }

    $token = md5(uniqid(rand(), true));
    $_SESSION['form_tokens_'.$name][count($_SESSION['form_tokens_'.$name])] = $token;
    return $token;
}

function validateToken(string $name) : Validation {
    if(!isset($_POST['form_name'])) {
        return new Validation(false, ValidationCode::NO_FORM_NAME);
    }

    if($_POST['form_name'] !== $name) {
        return new Validation(false, ValidationCode::WRONG_FORM_NAME);
    }

    if(!isset($_POST['form_token'])) {
        return new Validation(false, ValidationCode::NO_FORM_TOKEN);
    }

    if(!isset($_SESSION['form_tokens_'.$name]) || !in_array($_POST['form_token'], $_SESSION['form_tokens_'.$name], true)) {
        return new Validation(false, ValidationCode::INVALID_TOKEN);
    }

    return new Validation(true, ValidationCode::OK);
}

class ValidationCode {
    const OK = 0;
    const NO_FORM_NAME = 1;
    const WRONG_FORM_NAME = 2;
    const NO_FORM_TOKEN = 3;
    const INVALID_TOKEN = 4;
}

class Validation {
    private $_success = false;
    private $_code = -1;

    function __construct(bool $success, int $code) {
        $this->_success = $success;
        $this->_code = $code;
        if($code < 0 || $code > 4 || ($code === 0 && !$success) || ($code !== 0 && $success)) throw new \InvalidArgumentException("Validation Code ".$code." is not valid.");
    }

    public function isSuccessfull() : bool {
        return $this->_success;
    }

    public function getCode() : int {
        return $this->_code;
    }
}
