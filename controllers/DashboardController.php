<?php
// controllers/DashboardController.php

// Carga funciones de utilidad y la conexion a la base de datos
require_once 'core/functions.php';
require_once 'core/db_connection.php';

class DashboardController {

    public function index() {
        check_access(); // Verifica que el usuario este autenticado para acceder
        $rol = get_current_user_role(); // Obtiene el rol del usuario actual
        $resumen = []; // Array para almacenar los datos del resumen

        // La logica del dashboard solo se ejecuta para el rol 'administrador'
        if ($rol === 'administrador') {
            global $pdo; // Accede a la conexion a la base de datos
            try {
                // Consulta para obtener el total de clientes
                $resumen['clientes'] = $pdo->query("SELECT COUNT(*) FROM clients")->fetchColumn();
                
                // Consulta para obtener el conteo de oportunidades agrupadas por estado (estat)
                $stmt_op = $pdo->query("SELECT estat, COUNT(*) as total FROM oportunitats GROUP BY estat");
                $resumen['oportunidades'] = $stmt_op->fetchAll();
                
                // Consulta para contar el total de tareas pendientes
                $resumen['tareas_pendientes'] = $pdo->query("SELECT COUNT(*) FROM tasques WHERE estat = 'pendent'")->fetchColumn();
            } catch (PDOException $e) {
                // Captura y registra errores de la base de datos
                $error = "Error al cargar el resumen: " . $e->getMessage();
            }
        }

        // Carga la vista para mostrar el dashboard (incluyendo el array $resumen y la variable $error si existe)
        require 'views/dashboard.php';
    }
}
?>