<?php
// models/Usuario.php

class Usuario {
    private $pdo; // Almacena la conexion PDO

    public function __construct($pdo) {
        // Inicializa el modelo con la instancia de la base de datos
        $this->pdo = $pdo;
    }

    public function getAll() {
        // Obtiene todos los usuarios, excluyendo la contrasena por seguridad
        $stmt = $this->pdo->query("SELECT id_usuari, nom_usuari, nom_complet, email, rol FROM usuaris ORDER BY id_usuari ASC");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        // Obtiene un usuario por ID, excluyendo la contrasena
        $stmt = $this->pdo->prepare("SELECT id_usuari, nom_usuari, nom_complet, email, rol FROM usuaris WHERE id_usuari = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        // **SEGURIDAD**: Genera un hash seguro de la contrasena antes de guardarla
        $contrasenya_hash = password_hash($data['contrasenya'], PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO usuaris (nom_usuari, contrasenya, nom_complet, email, rol) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['nom_usuari'],
            $contrasenya_hash,
            $data['nom_complet'], // CORRECCIÓN
            $data['email'],      // CORRECCIÓN
            $data['rol']
        ]);
    }

    public function update($id, $data) {
        // SQL base para actualizar datos (sin contrasena)
        $sql = "UPDATE usuaris SET nom_usuari=?, nom_complet=?, email=?, rol=?";
        $params = [
            $data['nom_usuari'],
            $data['nom_complet'],
            $data['email'],
            $data['rol']
        ];

        // **ACTUALIZACION CONDICIONAL DE CONTRASENA**: Solo actualiza si se proporciona una nueva contrasena
        if (!empty($data['contrasenya'])) {
            $contrasenya_hash = password_hash($data['contrasenya'], PASSWORD_DEFAULT); // Hashea la nueva contrasena
            $sql .= ", contrasenya=?"; // Anade el campo contrasena a la consulta
            $params[] = $contrasenya_hash; // Anade el hash a los parametros
        }

        $sql .= " WHERE id_usuari=?";
        $params[] = $id;

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params); // Ejecuta la actualizacion
    }

    public function delete($id) {
        // Elimina un usuario por ID
        $stmt = $this->pdo->prepare("DELETE FROM usuaris WHERE id_usuari = ?");
        return $stmt->execute([$id]);
    }

    public function getByUsername($username) {
        // Usado para el proceso de Login: Obtiene la contrasena (hash) y el rol
        $stmt = $this->pdo->prepare("SELECT id_usuari, contrasenya, rol, nom_complet FROM usuaris WHERE nom_usuari = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(); // Devuelve los datos para la verificacion de credenciales
    }
}
?>