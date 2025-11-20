<?php
// clientes_delete.php - Eliminar Cliente (CRUD: Delete)
require_once 'includes/db_connection.php';
require_once 'includes/functions.php';

// Requiere estar logueado para acceder
check_access(); 

$rol = get_current_user_role();
$user_id = $_SESSION['user_id'];
$id_cliente = $_GET['id'] ?? null;
$error = '';

if (!$id_cliente) {
    $error = "ID de cliente no proporcionado.";
    header("Location: clientes.php?error=" . urlencode($error));
    exit();
}

try {
    // 1. Verificar si el cliente existe y quién es el responsable
    $stmt = $pdo->prepare("SELECT usuario_responsable, nom_complet FROM clients WHERE id_client = ?");
    $stmt->execute([$id_cliente]);
    $cliente_data = $stmt->fetch();

    if (!$cliente_data) {
        $error = "Cliente no encontrado.";
        header("Location: clientes.php?error=" . urlencode($error));
        exit();
    }
    
    // 2. Control de Acceso para Eliminar (Según Rol)
    // El vendedor solo puede eliminar sus propios clientes. El administrador puede eliminar cualquiera.
    $es_responsable = ($cliente_data['usuario_responsable'] == $user_id);
    
    if ($rol === 'venedor' && !$es_responsable) {
        $error = "Acceso denegado. Solo puedes eliminar tus propios clientes.";
        header("Location: clientes.php?error=" . urlencode($error));
        exit();
    }

    // 3. Ejecutar la eliminación
    $stmt_delete = $pdo->prepare("DELETE FROM clients WHERE id_client = ?");
    $stmt_delete->execute([$id_cliente]);

    $mensaje = "Cliente **" . htmlspecialchars($cliente_data['nom_complet']) . "** y sus datos asociados eliminados con éxito.";
    
    // Redirigir de vuelta al listado con un mensaje de éxito
    header("Location: clientes.php?msg=" . urlencode($mensaje));
    exit();

} catch (PDOException $e) {
    $error = "Error al eliminar el cliente: " . $e->getMessage();
    header("Location: clientes.php?error=" . urlencode($error));
    exit();
}
?>