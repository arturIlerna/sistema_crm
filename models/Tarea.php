<?php
// models/Tarea.php

class Tarea {
    private $pdo; // Almacena la conexion PDO

    public function __construct($pdo) {
        // Asigna la instancia de PDO
        $this->pdo = $pdo;
    }

    public function getAll($user_id, $rol, $pendientes = false) {
        // Consulta base para obtener tareas, uniendo informacion de Oportunidades, Clientes y Responsables
        $sql = "SELECT t.*, o.titol AS op_titulo, c.nom_complet AS cliente_nombre, u.nom_complet AS responsable_nombre
                FROM tasques t
                LEFT JOIN oportunitats o ON t.id_oportunitat = o.id_oportunitat
                LEFT JOIN clients c ON o.id_client = c.id_client
                JOIN usuaris u ON t.usuario_responsable = u.id_usuari
                WHERE 1=1"; // Clausula base

        $params = []; // Array para parametros de la consulta preparada

        // **FILTRO POR ROL**: Si es 'venedor', solo ve las tareas asignadas a el
        if ($rol === 'venedor') {
            $sql .= " AND t.usuario_responsable = ?";
            $params[] = $user_id;
        }

        // **FILTRO POR ESTADO**: Si $pendientes es true, solo trae tareas pendientes
        if ($pendientes) {
            $sql .= " AND t.estat = 'pendent'";
        }

        $sql .= " ORDER BY t.fecha ASC"; // Ordena por fecha

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params); // Ejecuta la consulta
        return $stmt->fetchAll(); // Devuelve todas las tareas
    }

    public function create($data) {
        // Inserta una nueva tarea. El estado inicial se fija automaticamente a 'pendent'
        $sql = "INSERT INTO tasques (id_oportunitat, descripcio, fecha, estat, usuario_responsable)
                VALUES (?, ?, ?, 'pendent', ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['id_oportunitat'],
            $data['descripcio'],
            $data['fecha'],
            $data['usuario_responsable']
        ]); // Ejecuta la insercion
    }

    public function complete($id) {
        // Actualiza el estado de una tarea a 'completada'
        $stmt = $this->pdo->prepare("UPDATE tasques SET estat = 'completada' WHERE id_tarea = ?");
        return $stmt->execute([$id]); // Ejecuta la actualizacion
    }
}
?>