<?php
require_once($_SERVER['DOCUMENT_ROOT']."/fdl/app/models/auth/authenticator.php");
class AuthController{
    private $model;

    public function __construct()
    {
        $this->model = new Authenticator();

    }

    public function login($form)
    {
        $rows = $this->model->login($form);
       header("Location: index.php?route=registro&method=dashboard");
exit;
    }

    public function logout()
    {
        $rows = $this->model->logout();
        header('location: ?');
    }


}
