<?php
// controllers/ClienteController.php

// Carga el modelo Cliente, la conexion a DB y funciones de utilidad
require_once 'models/Cliente.php';
require_once 'core/db_connection.php';
require_once 'core/functions.php';

class ClienteController {

    private $clienteModel;

    public function __construct() {
        global $pdo;
        // Inicializa el modelo Cliente para las operaciones de DB
        $this->clienteModel = new Cliente($pdo);
    }

    public function index() {
        check_access(); // Verifica si el usuario tiene permiso para acceder a esta pagina
        $rol = get_current_user_role();
        $user_id = $_SESSION['user_id'];
        $search = $_GET['search'] ?? ''; // Obtiene el termino de busqueda

        // Obtiene todos los clientes. La funcion aplica filtrado segun el rol del usuario y el termino de busqueda.
        $clientes = $this->clienteModel->getAll($user_id, $rol, $search);

        // Cargar la vista que mostrara el listado de clientes
        require 'views/clientes/index.php';
    }

    public function create() {
        check_access(); 
        $rol = get_current_user_role();     // CORRECCIÓN: Definir $rol
        $user_id = $_SESSION['user_id'];    // CORRECCIÓN: Definir $user_id

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom_complet' => $_POST['nom_complet'],
                'empresa' => $_POST['empresa'],     // CORRECCIÓN: Añadido
                'email' => $_POST['email'],         // CORRECCIÓN: Añadido
                'tlf' => $_POST['tlf'],             // CORRECCIÓN: Añadido
                'usuario_responsable' => ($rol === 'administrador') ? $_POST['usuario_responsable'] : $user_id
            ];
            $this->clienteModel->create($data); // Llama al modelo para crear el registro
            header("Location: index.php?controller=cliente&action=index"); // Redirecciona al listado
        } else {
            // Muestra el formulario de creacion
            require 'views/clientes/form.php';
        }
    }

    public function edit() {
        check_access(); // Verifica el acceso
        $id = $_GET['id']; // ID del cliente a editar

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recolecta los datos de edicion, incluyendo la logica para el responsable
            $data = [
                // ...
                'usuario_responsable' => ($rol === 'administrador') ? $_POST['usuario_responsable'] : $user_id
            ];
            // Llama al modelo para actualizar el cliente. Incluye $user_id y $rol para posibles restricciones de edicion.
            $this->clienteModel->update($id, $data, $user_id, $rol);
            header("Location: index.php?controller=cliente&action=index"); // Redirecciona
        } else {
            // Si es GET, obtiene los datos del cliente para llenar el formulario
            $cliente = $this->clienteModel->getById($id);
            // Cargar la vista del formulario
            require 'views/clientes/form.php';
        }
    }

    public function delete() {
        check_access(); // Verifica el acceso
        $id = $_GET['id'];
        $this->clienteModel->delete($id); // Llama al modelo para eliminar el cliente
        header("Location: index.php?controller=cliente&action=index"); // Redirecciona
    }
}
?>