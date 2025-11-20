<!DOCTYPE html>
<html lang="es">
<head>
    <title>Gestión de Usuarios - CRM (Admin)</title>
</head>
<body>
    <h1>Gestión de Usuarios (Administrador)</h1>
    <p><a href="index.php?controller=dashboard&action=index">Volver al Dashboard</a> | <a href="index.php?controller=auth&action=logout">Cerrar Sesión</a></p>
    <hr>

    <?php if (isset($mensaje)): ?>
        <p style="color: green; font-weight: bold;"><?php echo $mensaje; ?></p>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <p style="color: red; font-weight: bold;"><?php echo $error; ?></p>
    <?php endif; ?>

    <h2>Listado de Usuarios</h2>
    <p><a href="index.php?controller=usuario&action=create">➕ Crear Nuevo Usuario</a></p>

    <table border="1" style="width: 70%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Nombre Completo</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?php echo $u['id_usuari']; ?></td>
                    <td><?php echo htmlspecialchars($u['nom_usuari']); ?></td>
                    <td><?php echo htmlspecialchars($u['nom_complet']); ?></td>
                    <td><?php echo ucfirst($u['rol']); ?></td>
                    <td>
                        <a href="index.php?controller=usuario&action=edit&id=<?php echo $u['id_usuari']; ?>">Editar</a>
                        <?php if ($u['id_usuari'] != $_SESSION['user_id']): ?>
                            | <a href="index.php?controller=usuario&action=delete&id=<?php echo $u['id_usuari']; ?>" onclick="return confirm('¿Estás seguro de eliminar a <?php echo htmlspecialchars($u['nom_usuari']); ?>?');">Eliminar</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>