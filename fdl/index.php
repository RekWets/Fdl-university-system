<?php

session_start();

$auth = false;
if (isset($_SESSION) and !empty($_SESSION)) {
    $auth = true;
}
$route = (isset($_REQUEST['route'])) ? $_REQUEST['route'] : "";
$method = (isset($_REQUEST['method'])) ? $_REQUEST['method'] : "";

if ($route === "auth") {
    require_once('app/controllers/auth/authController.php');
    $auth = new AuthController();
    switch ($method) {
        case 'login':
            $auth->login($_POST);
            break;

        case 'logout':
            $auth->logout($_POST);
            break;
    }
}
if ($auth) {
    if (!empty($route) && !empty($method)) {
        switch ($route) {
            case 'registro':
                require_once('app/controllers/registroController.php');
                $table = new registroController();
                switch ($method) {
                    case 'dashboard':
                        $table->dashboard();
                        break;

                    case 'registro':
                        $table->registro();
                        break;

                    case 'consulta':
                        $table->consulta($_POST);
                        break;

                    case 'guardar':
                        $table->guardar();
                        break;

                    case 'editar':
                        $table->editar($_POST);
                        break;
                        
                    case 'actualizar':
                        $table->actualizar();
                        break;
                    case 'ficha':
                        $table->ficha($_POST);
                        break;

                    case 'eliminar':
                        $table->eliminar($_POST);
                        break;

                    case 'inactivos':
                        $table->inactivos($_POST);
                        break;

                    case 'activar':
                        $table->activar($_POST);
                        break;

                    case 'ajustes':
                        $table->ajustes($_POST);
                        break;

                    case 'cambiarPassword':
                         $table->cambiarPassword($_POST);
                        break;

                        $table->dashboard();
                        break;
                }
                break;
        }
    } else {
        $title = "Escritorio";
        header("Location: index.php?route=registro&method=dashboard");
        exit;
    }
} else {
    $title = "Iniciar Sesión";
    include("app/views/auth/login.php");
}
