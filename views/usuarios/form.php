<!DOCTYPE html>
<html lang="es">
<head>
    <title><?php echo isset($usuario) ? 'Editar Usuario' : 'Crear Usuario'; ?></title>
</head>
<body>
    <h1><?php echo isset($usuario) ? '✏️ Editar Usuario' : '➕ Crear Nuevo Usuario'; ?></h1>
    <p><a href="index.php?controller=usuario&action=index">Volver al Listado de Usuarios</a> | <a href="index.php?controller=auth&action=logout">Cerrar Sesión</a></p>
    <hr>

    <form action="index.php?controller=usuario&action=<?php echo isset($usuario) ? 'edit&id=' . $usuario['id_usuari'] : 'create'; ?>" method="POST">
        <input type="hidden" name="id_usuari" value="<?php echo $usuario['id_usuari'] ?? ''; ?>">

        <label>Nombre de Usuario:</label>
        <input type="text" name="nom_usuari" value="<?php echo htmlspecialchars($usuario['nom_usuari'] ?? ''); ?>" required><br><br>

        <label>Nombre Completo:</label>
        <input type="text" name="nom_complet" value="<?php echo htmlspecialchars($usuario['nom_complet'] ?? ''); ?>" required><br><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email'] ?? ''); ?>" required><br><br>

        <label>Contraseña: (<?php echo isset($usuario) ? 'Dejar vacío para no cambiar' : 'Requerida'; ?>)</label>
        <input type="password" name="contrasenya"><br><br>

        <label>Rol:</label>
        <select name="rol" required>
            <option value="administrador" <?php echo (isset($usuario['rol']) && $usuario['rol'] == 'administrador') ? 'selected' : ''; ?>>Administrador</option>
            <option value="venedor" <?php echo (isset($usuario['rol']) && $usuario['rol'] == 'venedor') ? 'selected' : ''; ?>>Vendedor</option>
        </select><br><br>

        <button type="submit"><?php echo isset($usuario) ? 'Guardar Cambios' : 'Crear Usuario'; ?></button>
        <a href="index.php?controller=usuario&action=index">Cancelar</a>
    </form>
</body>
</html>