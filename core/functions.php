<?php
// includes/functions.php
// Inicia la sesion de PHP. 
session_start();

/**
 * Verifica si el usuario esta actualmente logueado.
 */
function is_logged_in() {
    // Retorna TRUE si existe la clave 'user_id' en la sesion
    return isset($_SESSION['user_id']);
}

/**
 * Obtiene el rol del usuario actual.
 */
function get_current_user_role() {
    // Retorna el rol o null si no existe
    return isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
}

/**
 * Funcion de proteccion: Redirige al login o al dashboard si el rol no es suficiente.
 * @param string|null $required_role Rol requerido para acceder.
 */
function check_access($required_role = null) {
    // 1. Verifica si NO hay sesion iniciada
    if (!is_logged_in()) {
        header("Location: index.php"); // Redirige al login y detiene la ejecucion
        exit();
    }
    
    $current_role = get_current_user_role();

    // 2. Si se especifico un rol requerido ($required_role no es null)
    if ($required_role !== null) {
        // Logica de autorizacion
        // Si el rol NO coincide con el requerido Y el usuario NO es administrador...
        if ($current_role !== $required_role && $current_role !== 'administrador') {
            // ... entonces redirige con un error de acceso denegado
            header("Location: dashboard.php?error=acceso_denegado"); 
            exit();
        }
        // Nota: El administrador ('administrador') siempre pasa la verificacion
    }
}
?>