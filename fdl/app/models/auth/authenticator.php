<?php
require_once($_SERVER['DOCUMENT_ROOT']."/fdl/app/db/db.php");

class Authenticator extends BDConexion{
    public $conn;
    
    public function __construct(){
        $this->conn = parent::__construct();

    }

   public function login($form) {
    // 1. Buscamos al usuario solo por su identificador (email o username)
    // Eliminamos la contraseña del SQL
    $sql = "SELECT * FROM users WHERE (username = :email OR email = :email) AND status = 'si'";
    $pdo = $this->conn->prepare($sql);        
    $pdo->bindParam(':email', $form['email']);

    if($pdo->execute()) {
        // Obtenemos el usuario
        $user_data = $pdo->fetch(PDO::FETCH_ASSOC);

        if($user_data) {
            // 2. Verificamos si la contraseña plana coincide con el hash de la BD
            if(password_verify($form['password'], $user_data['password'])) {
                
                // Si es correcto, guardamos en la sesión
                // Nota: Usamos 'row_count_0' para mantener compatibilidad con tu código actual
                $_SESSION['row_count_0'] = $user_data;
                
                return true;
            } else {
                // Contraseña incorrecta
                return false; 
            }
        } else {
            // Usuario no encontrado o inactivo
            return false;
        }
    }
    return false;
}

    public function logout()
    {
        session_destroy();
        return true;
    }
}