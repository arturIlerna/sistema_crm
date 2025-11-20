<?php
// models/Cliente.php

class Cliente {
    private $pdo; // Almacena la conexion PDO

    public function __construct($pdo) {
        // Inicializa el modelo con la instancia de la base de datos (PDO)
        $this->pdo = $pdo; 
    }

    public function getAll($user_id, $rol, $search = '') {
        // Consulta base para obtener clientes y el nombre completo del usuario responsable (join con usuaris)
        $sql = "SELECT c.id_client, c.nom_complet, c.empresa, c.email, c.tlf, c.fecha_registro,
                         c.usuario_responsable, u.nom_complet as responsable_nom /* Alias para el nombre del responsable */
                FROM clients c
                JOIN usuaris u ON c.usuario_responsable = u.id_usuari
                WHERE 1=1"; // Clausula base

        $params = []; // Array para almacenar los parametros de la consulta preparada

        // FILTRO POR ROL: Si es 'venedor', solo ve los clientes donde el es el responsable
        if ($rol === 'venedor') {
            $sql .= " AND c.usuario_responsable = ?";
            $params[] = $user_id;
        }

        // FILTRO POR BUSQUEDA: Si se proporciona un termino ($search)
        if (!empty($search)) {
            // Busca coincidencias en nombre completo o empresa (LIKE para busqueda parcial)
            $sql .= " AND (c.nom_complet LIKE ? OR c.empresa LIKE ?)";
            $params[] = "%$search%"; // Prepara el parametro de busqueda 1
            $params[] = "%$search%"; // Prepara el parametro de busqueda 2
        }

        $sql .= " ORDER BY c.nom_complet"; // Ordena los resultados

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params); // Ejecuta la consulta con los parametros de filtrado
        return $stmt->fetchAll(); // Devuelve todos los resultados
    }

    public function getById($id) {
        // Obtiene un cliente por su ID
        $stmt = $this->pdo->prepare("SELECT * FROM clients WHERE id_client = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(); // Devuelve un unico registro
    }

    public function create($data) {
        // Inserta un nuevo cliente
        $sql = "INSERT INTO clients (nom_complet, empresa, email, tlf, usuario_responsable) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        // Ejecuta la insercion con los datos proporcionados
        return $stmt->execute([
            $data['nom_complet'],
            $data['empresa'],
            $data['email'],
            $data['tlf'],
            $data['usuario_responsable']
        ]);
    }

    public function update($id, $data, $user_id, $rol) {
        // LOGICA DE PERMISOS: Diferencia la actualizacion segun el rol
        if ($rol === 'administrador') {
            // El administrador puede actualizar todos los campos, incluido el responsable
            $sql = "UPDATE clients SET nom_complet=?, empresa=?, email=?, tlf=?, usuario_responsable=? WHERE id_client=?";
            $params = [
                $data['nom_complet'],
                $data['empresa'],
                $data['email'],
                $data['tlf'],
                $data['usuario_responsable'],
                $id
            ];
        } else {
            // El vendedor solo puede actualizar si es el responsable del cliente
            $sql = "UPDATE clients SET nom_complet=?, empresa=?, email=?, tlf=? WHERE id_client=? AND usuario_responsable=?";
            $params = [
                $data['nom_complet'],
                $data['empresa'],
                $data['email'],
                $data['tlf'],
                $id,
                $user_id // Condicion de seguridad: el ID de usuario logueado debe coincidir con el responsable
            ];
        }
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params); // Ejecuta la actualizacion
    }

    public function delete($id) {
        // Elimina un cliente por ID
        $stmt = $this->pdo->prepare("DELETE FROM clients WHERE id_client = ?");
        return $stmt->execute([$id]);
    }
}
?>