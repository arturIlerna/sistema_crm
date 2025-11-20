<!DOCTYPE html>
<html lang="es">
<head>
    <title><?php echo isset($cliente) ? 'Editar Cliente' : 'Crear Cliente'; ?></title>
</head>
<body>
    <h1><?php echo isset($cliente) ? '✏️ Editar Cliente' : '➕ Crear Nuevo Cliente'; ?></h1>
    <p><a href="index.php?controller=cliente&action=index">Volver al Listado de Clientes</a> | <a href="index.php?controller=auth&action=logout">Cerrar Sesion</a></p>
    <hr>

    <form action="index.php?controller=cliente&action=<?php echo isset($cliente) ? 'edit&id=' . $cliente['id_client'] : 'create'; ?>" method="POST">
        <input type="hidden" name="id_client" value="<?php echo $cliente['id_client'] ?? ''; ?>">

        <label for="nom_complet">Nombre Completo (*):</label>
        <input type="text" name="nom_complet" id="nom_complet" value="<?php echo htmlspecialchars($cliente['nom_complet'] ?? ''); ?>" required><br><br>

        <label for="empresa">Empresa:</label>
        <input type="text" name="empresa" id="empresa" value="<?php echo htmlspecialchars($cliente['empresa'] ?? ''); ?>"><br><br>

        <label for="email">Email (*):</label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($cliente['email'] ?? ''); ?>" required><br><br>

        <label for="tlf">Telefono:</label>
        <input type="text" name="tlf" id="tlf" value="<?php echo htmlspecialchars($cliente['tlf'] ?? ''); ?>"><br><br>

        <?php
        // Solo el administrador ve el selector de responsable
        if ($rol === 'administrador'):
            // Se incluye el modelo de Usuario y se obtiene la lista de usuarios. Esto es logica de vista.
            require_once 'models/Usuario.php';
            global $pdo;
            $usuarioModel = new Usuario($pdo);
            $usuarios_responsables = $usuarioModel->getAll();
        ?>
            <label for="usuario_responsable">Vendedor Responsable:</label>
            <select name="usuario_responsable" id="usuario_responsable" required>
                <?php foreach ($usuarios_responsables as $u): ?>
                    <option value="<?php echo $u['id_usuari']; ?>"
                             <?php
                             // Determina el ID seleccionado: usa el responsable actual del cliente o el ID del usuario logueado como default
                             $selected_id = $cliente['usuario_responsable'] ?? $user_id;
                             // Marca la opcion como 'selected' si coincide
                             if ($u['id_usuari'] == $selected_id) echo 'selected';
                             ?>
                    >
                        <?php echo htmlspecialchars($u['nom_complet']) . ' (' . ucfirst($u['rol']) . ')'; ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>
        <?php else: ?>
            <input type="hidden" name="usuario_responsable" value="<?php echo $user_id; ?>">
            <p>**Responsable:** <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
        <?php endif; ?>

        <button type="submit"><?php echo isset($cliente) ? 'Guardar Cambios' : 'Crear Cliente'; ?></button>
        <a href="index.php?controller=cliente&action=index">Cancelar</a>
    </form>
</body>
</html>