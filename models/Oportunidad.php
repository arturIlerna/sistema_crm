<?php
// models/Oportunidad.php

class Oportunidad {
    private $pdo;

    public function __construct($pdo) {
        // Inicializa el modelo con la conexion a la DB (PDO)
        $this->pdo = $pdo;
    }

    public function getAll($user_id, $rol, $estado = '', $cliente_id = '') {
        // Consulta base: Selecciona campos de oportunidad (o), cliente (c) y usuario responsable (u)
        $sql = "SELECT o.id_oportunitat, o.titol, o.valor_estimat, o.estat, o.fecha_creacion,
                         c.nom_complet AS nombre_cliente,  /* Alias para el nombre del cliente */
                         u.nom_complet AS nombre_responsable /* Alias para el nombre del responsable */
                FROM oportunitats o
                JOIN clients c ON o.id_client = c.id_client
                JOIN usuaris u ON o.usuario_responsable = u.id_usuari
                WHERE 1=1"; // Clausula base para facilitar filtros condicionales

        $params = []; // Array para parametros de la consulta preparada

        // FILTRO POR ROL: El vendedor solo ve las oportunidades donde es responsable
        if ($rol === 'venedor') {
            $sql .= " AND o.usuario_responsable = ?";
            $params[] = $user_id;
        }

        // FILTRO POR ESTADO: Filtra por el estado de la oportunidad si se proporciona
        if (!empty($estado)) {
            $sql .= " AND o.estat = ?";
            $params[] = $estado;
        }

        // FILTRO POR CLIENTE: Filtra por el ID del cliente si se proporciona
        if (!empty($cliente_id)) {
            $sql .= " AND o.id_client = ?";
            $params[] = $cliente_id;
        }

        $sql .= " ORDER BY o.fecha_creacion DESC"; // Ordena por fecha de creacion descendente

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params); // Ejecuta la consulta con todos los filtros aplicados
        return $stmt->fetchAll();
    }

    public function getById($id) {
        // Obtiene una oportunidad por su ID
        $stmt = $this->pdo->prepare("SELECT * FROM oportunitats WHERE id_oportunitat = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        // Inserta una nueva oportunidad en la base de datos
        $sql = "INSERT INTO oportunitats (titol, descripcio, valor_estimat, id_client, estat, usuario_responsable) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['titol'],
            $data['descripcio'],
            // ... (otros campos)
            $data['usuario_responsable']
        ]);
    }

    public function update($id, $data, $user_id, $rol) {
        // SQL base para actualizar la mayoria de campos
        $sql = "UPDATE oportunitats SET titol=?, descripcio=?, valor_estimat=?, id_client=?, estat=?";
        $params = [
            $data['titol'],
            $data['descripcio'],
            $data['valor_estimat'],
            $data['id_client'],
            $data['estat']
        ];

        // LOGICA DE PERMISOS: Solo el administrador puede cambiar el usuario responsable
        if ($rol === 'administrador') {
            $sql .= ", usuario_responsable=?"; // Agrega el campo responsable a la consulta
            $params[] = $data['usuario_responsable'];
            $sql .= " WHERE id_oportunitat=?"; // Condicion de busqueda por ID
            $params[] = $id;
        } else {
            // El vendedor solo puede actualizar si es el responsable de esta oportunidad
            $sql .= " WHERE id_oportunitat=? AND usuario_responsable=?";
            $params[] = $id;
            $params[] = $user_id; // Restringe la actualizacion al responsable actual
        }
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params); // Ejecuta la actualizacion
    }

    public function delete($id) {
        // Elimina una oportunidad por ID
        $stmt = $this->pdo->prepare("DELETE FROM oportunitats WHERE id_oportunitat = ?");
        return $stmt->execute([$id]);
    }
}
?>