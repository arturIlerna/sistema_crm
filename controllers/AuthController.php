<?php
// controllers/AuthController.php

// Carga el modelo de usuario, la conexion a DB y funciones de ayuda
require_once 'models/Usuario.php'; 
require_once 'core/db_connection.php'; 
require_once 'core/functions.php'; 

class AuthController {

    private $usuarioModel;

    public function __construct() {
        global $pdo;
        // Inicializa el modelo Usuario para acceso a la DB
        $this->usuarioModel = new Usuario($pdo); 
    }

    public function login() {
        // Redirige si el usuario ya esta autenticado
        if (is_logged_in()) {
            header("Location: index.php?controller=dashboard&action=index");
            exit();
        }

        $error = '';

        // Procesa el envio del formulario de login
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom_usuari = $_POST['nombre_usuario'] ?? '';
            $password = $_POST['password'] ?? '';

            // Obtiene el usuario de la DB
            $user = $this->usuarioModel->getByUsername($nom_usuari);

            // Verifica usuario y contrasena (uso de password_verify para el hash)
            if ($user && password_verify($password, $user['contrasenya'])) {
                // Autenticacion exitosa: establece variables de sesion
                $_SESSION['user_id'] = $user['id_usuari'];
                $_SESSION['user_role'] = $user['rol'];
                $_SESSION['user_name'] = $user['nom_complet'];

                // Redirige al dashboard
                header("Location: index.php?controller=dashboard&action=index");
                exit();
            } else {
                $error = "Nombre de usuario o contrasena incorrectos.";
            }
        }

        // Muestra el formulario de login
        require 'views/auth/login.php';
    }

    public function logout() {
        session_start();
        session_destroy(); // Cierra y destruye la sesion
        // Redirige a la pagina principal
        header("Location: index.php");
        exit();
    }
}
?>