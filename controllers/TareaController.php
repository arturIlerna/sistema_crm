<?php
// controllers/TareaController.php

// Carga los modelos Tarea y Oportunidad, la conexion DB y funciones
require_once 'models/Tarea.php';
require_once 'models/Oportunidad.php';
require_once 'core/db_connection.php';
require_once 'core/functions.php';

class TareaController {

    private $tareaModel;
    private $oportunidadModel;

    public function __construct() {
        global $pdo;
        // Inicializa ambos modelos (Tarea y Oportunidad) con la conexion DB
        $this->tareaModel = new Tarea($pdo);
        $this->oportunidadModel = new Oportunidad($pdo);
    }

    public function index() {
        check_access(); // Verifica el acceso
        $rol = get_current_user_role();
        $user_id = $_SESSION['user_id'];
        // Determina si se debe aplicar el filtro para ver solo tareas pendientes
        $filtro_pendiente = isset($_GET['pendientes']) && $_GET['pendientes'] == 'true';

        // Maneja la creacion de tareas (Formulario de creacion integrado en el index)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id_oportunitat' => $_POST['id_oportunitat'],
                'descripcio' => $_POST['descripcion'], // CORRECCIÓN: POST usa 'descripcion', el modelo espera 'descripcio'
                'fecha' => $_POST['fecha'],
                'usuario_responsable' => $user_id 
            ];
            $this->tareaModel->create($data); // Crea la nueva tarea
            header("Location: index.php?controller=tarea&action=index"); // Redirecciona para evitar reenvio
        }

        // Obtiene la lista de tareas aplicando los filtros de usuario, rol y estado pendiente
        $tareas = $this->tareaModel->getAll($user_id, $rol, $filtro_pendiente);
        // Obtiene la lista de oportunidades para el selector del formulario de creacion
        $oportunidades_list = $this->oportunidadModel->getAll($user_id, $rol);

        // Cargar la vista principal del listado de tareas
        require 'views/tareas/index.php';
    }

    public function complete() {
        check_access(); // Verifica el acceso
        $id = $_GET['id']; // ID de la tarea a completar
        $this->tareaModel->complete($id); // Llama al modelo para marcar la tarea como completada
        header("Location: index.php?controller=tarea&action=index"); // Redirecciona al listado
    }
}
?>