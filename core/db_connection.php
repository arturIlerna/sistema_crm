<?php

// Configuracion de la base de datos
$host = 'localhost'; // Servidor de la base de datos
$db   = 'crm_ilerna'; // Nombre de la base de datos
$user = 'root';   // Usuario de la base de datos
$pass = '';       // Contrasena del usuario
$charset = 'utf8mb4'; // Codificacion de caracteres

// DSN (Data Source Name): Cadena que contiene la informacion necesaria para conectar
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    // Configuracion de PDO (PHP Data Objects)
    // 1. Manejo de errores: Lanza excepciones en caso de error SQL
    PDO::ATTR_ERRMODE              => PDO::ERRMODE_EXCEPTION,
    // 2. Modo de obtencion de datos por defecto: Devuelve los datos como array asociativo
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // 3. Desactiva la emulacion de preparacion de sentencias para mas seguridad y rendimiento
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     // Intenta crear la nueva instancia de PDO (establecer la conexion)
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     // Captura cualquier error de conexion PDO
     // Detiene el script y muestra el mensaje de error de conexion si falla
     die("Conexion fallida: " . $e->getMessage());
}
?>