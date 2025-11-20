<?php
// controllers/UsuarioController.php

// Carga el modelo Usuario, la conexion DB y funciones
require_once 'models/Usuario.php';
require_once 'core/db_connection.php';
require_once 'core/functions.php';

class UsuarioController {

    private $usuarioModel;

    public function __construct() {
        global $pdo;
        // Inicializa el modelo Usuario para las operaciones CRUD en la DB
        $this->usuarioModel = new Usuario($pdo);
    }

    public function index() {
        // Restringe el acceso solo a usuarios con rol 'administrador'
        check_access('administrador'); 
        $usuarios = $this->usuarioModel->getAll(); // Obtiene la lista completa de usuarios
        require 'views/usuarios/index.php'; // Muestra la vista del listado
    }

    public function create() {
        check_access('administrador'); // Restringe el acceso
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom_usuari' => $_POST['nom_usuari'],
                'nom_complet' => $_POST['nom_complet'],
                'email' => $_POST['email'],          // CORRECCIÓN
                'contrasenya' => $_POST['contrasenya'],
                'rol' => $_POST['rol']               // CORRECCIÓN
            ];
            $this->usuarioModel->create($data);
            header("Location: index.php?controller=usuario&action=index"); // Redirecciona
        } else {
            // Muestra el formulario de creacion
            require 'views/usuarios/form.php';
        }
    }

    public function edit() {
        check_access('administrador'); // Restringe el acceso
        $id = $_GET['id']; // ID del usuario a editar
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recolecta datos de edicion
            $data = [
                // ... campos a actualizar
                'contrasenya' => $_POST['contrasenya'] // La contrasena se actualiza si se proporciona
            ];
            $this->usuarioModel->update($id, $data); // Actualiza el usuario
            header("Location: index.php?controller=usuario&action=index"); // Redirecciona
        } else {
            // Si es GET, obtiene los datos del usuario para precargar el formulario
            $usuario = $this->usuarioModel->getById($id);
            require 'views/usuarios/form.php';
        }
    }

    public function delete() {
        check_access('administrador'); // Restringe el acceso
        $id = $_GET['id'];
        
        // **IMPORTANTE**: Verifica que el usuario que se va a eliminar no sea el usuario logueado actualmente
        if ($id != $_SESSION['user_id']) {
            $this->usuarioModel->delete($id); // Elimina el usuario
        }
        header("Location: index.php?controller=usuario&action=index"); // Redirecciona
    }
}
?>