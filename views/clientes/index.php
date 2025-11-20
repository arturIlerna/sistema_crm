<!DOCTYPE html>
<html lang="es">
<head>
    <title>Módulo de Clientes</title>
</head>
<body>
    <h1>Módulo de Clientes</h1>
    <p><a href="index.php?controller=dashboard&action=index">Volver al Dashboard</a> | <a href="index.php?controller=auth&action=logout">Cerrar Sesión</a></p>
    <hr>

    <form method="GET" action="index.php" style="margin-bottom: 20px;">
        <input type="hidden" name="controller" value="cliente">
        <input type="hidden" name="action" value="index">
        <input type="text" name="search" placeholder="Buscar por Nombre o Empresa" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Buscar</button>
        <?php if (!empty($search)): ?>
            <a href="index.php?controller=cliente&action=index">Mostrar todos</a>
        <?php endif; ?>
    </form>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <p><a href="index.php?controller=cliente&action=create">➕ Crear Nuevo Cliente</a></p>

    <table border="1" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Nombre Completo</th>
                <th>Empresa</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Fecha Registro</th>
                <th>Responsable</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($clientes)): ?>
                <tr><td colspan="7">No se encontraron clientes que coincidan con los criterios.</td></tr>
            <?php endif; ?>
            <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?php echo htmlspecialchars($cliente['nom_complet']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['empresa']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['tlf']); ?></td>
                    <td><?php echo htmlspecialchars(substr($cliente['fecha_registro'], 0, 10)); ?></td>
                    <td><?php echo htmlspecialchars($cliente['responsable_nom']); ?></td>
                    <td>
                        <?php
                        $puede_modificar = ($rol === 'administrador' || $cliente['usuario_responsable'] == $user_id);

                        if ($puede_modificar):
                        ?>
                            <a href="index.php?controller=cliente&action=edit&id=<?php echo $cliente['id_client']; ?>">Editar</a>
                            | <a href="index.php?controller=cliente&action=delete&id=<?php echo $cliente['id_client']; ?>" onclick="return confirm('¿Seguro que deseas eliminar este cliente?');">Eliminar</a>
                        <?php else: ?>
                            *Solo lectura*
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>