<?php
// controllers/OportunidadController.php

// Carga los modelos Oportunidad y Cliente, la conexion DB y funciones
require_once 'models/Oportunidad.php';
require_once 'models/Cliente.php';
require_once 'core/db_connection.php';
require_once 'core/functions.php';

class OportunidadController {

    private $oportunidadModel;
    private $clienteModel;

    public function __construct() {
        global $pdo;
        // Inicializa ambos modelos (Oportunidad y Cliente) con la conexion DB
        $this->oportunidadModel = new Oportunidad($pdo);
        $this->clienteModel = new Cliente($pdo);
    }

    public function index() {
        check_access(); // Verifica el acceso del usuario
        $rol = get_current_user_role();
        $user_id = $_SESSION['user_id'];
        
        // Obtiene parametros de filtrado del GET
        $estado_filtro = $_GET['estado'] ?? '';
        $cliente_filtro = $_GET['id_cliente'] ?? '';

        // Obtiene las oportunidades aplicando filtros de usuario, rol y parametros de filtro
        $oportunidades = $this->oportunidadModel->getAll($user_id, $rol, $estado_filtro, $cliente_filtro);
        // Obtiene la lista de clientes para usar en los filtros de la vista
        $clientes_filtro = $this->clienteModel->getAll($user_id, $rol);

        // Cargar la vista principal de oportunidades
        require 'views/oportunidades/index.php';
    }

    public function create() {
        check_access(); // Verifica el acceso
        $rol = get_current_user_role();
        $user_id = $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'titol' => $_POST['titol'],                                 // CORRECCIÓN: Añadido
                'descripcio' => $_POST['descripcion'],                      // CORRECCIÓN: Añadido (mapeo 'descripcion' a 'descripcio')
                'valor_estimat' => $_POST['valor_estimat'],                 // CORRECCIÓN: Añadido
                'id_client' => $_POST['id_client'],                         // CORRECCIÓN: Añadido
                'estat' => $_POST['estat'],                                 // CORRECCIÓN: Añadido
                'usuario_responsable' => ($rol === 'administrador') ? $_POST['usuario_responsable'] : $user_id
            ];
            $this->oportunidadModel->create($data); // Crea la oportunidad en la DB
            header("Location: index.php?controller=oportunidad&action=index"); // Redirecciona
        } else {
            // Si es GET, obtiene la lista de clientes para rellenar el selector en el formulario
            $clientes_list = $this->clienteModel->getAll($user_id, $rol);
            // Cargar la vista del formulario
            require 'views/oportunidades/form.php';
        }
    }

    public function edit() {
        check_access(); // Verifica el acceso
        $id = $_GET['id']; // ID de la oportunidad a editar
        $rol = get_current_user_role();
        $user_id = $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recolecta datos (logica de responsable similar a 'create')
            $data = [
                // ... datos de la oportunidad
                'usuario_responsable' => ($rol === 'administrador') ? $_POST['usuario_responsable'] : $user_id
            ];
            // Actualiza la oportunidad, aplicando posibles reglas de negocio basadas en $user_id y $rol
            $this->oportunidadModel->update($id, $data, $user_id, $rol);
            header("Location: index.php?controller=oportunidad&action=index");
        } else {
            // Si es GET, obtiene los datos de la oportunidad y la lista de clientes
            $oportunidad = $this->oportunidadModel->getById($id);
            $clientes_list = $this->clienteModel->getAll($user_id, $rol);
            // Cargar la vista del formulario con datos precargados
            require 'views/oportunidades/form.php';
        }
    }

    public function delete() {
        check_access(); // Verifica el acceso
        $id = $_GET['id'];
        $this->oportunidadModel->delete($id); // Elimina la oportunidad
        header("Location: index.php?controller=oportunidad&action=index"); // Redirecciona
    }
}
?>