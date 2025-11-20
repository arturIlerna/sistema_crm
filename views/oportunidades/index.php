<!DOCTYPE html>
<html lang="es">
<head>
    <title>Módulo de Oportunidades</title>
</head>
<body>
    <h1>Módulo de Oportunidades</h1>
    <p><a href="index.php?controller=dashboard&action=index">Volver al Dashboard</a> | <a href="index.php?controller=auth&action=logout">Cerrar Sesión</a></p>
    <hr>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="GET" action="index.php" style="margin-bottom: 20px;">
        <input type="hidden" name="controller" value="oportunidad">
        <input type="hidden" name="action" value="index">
        <label for="estado">Filtrar por Estado:</label>
        <select name="estado" id="estado">
            <option value="">Todos los Estados</option>
            <option value="progreso" <?php if ($estado_filtro == 'progreso') echo 'selected'; ?>>En Progreso</option>
            <option value="guanyada" <?php if ($estado_filtro == 'guanyada') echo 'selected'; ?>>Ganada</option>
            <option value="perduda" <?php if ($estado_filtro == 'perduda') echo 'selected'; ?>>Perdida</option>
        </select>

        <label for="id_cliente">Filtrar por Cliente:</label>
        <select name="id_cliente" id="id_cliente">
            <option value="">Todos los Clientes</option>
            <?php foreach ($clientes_filtro as $cliente): ?>
                <option value="<?php echo $cliente['id_client']; ?>" <?php if ($cliente_filtro == $cliente['id_client']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($cliente['nom_complet']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Aplicar Filtros</button>
        <a href="index.php?controller=oportunidad&action=index">Quitar Filtros</a>
    </form>

    <p><a href="index.php?controller=oportunidad&action=create">➕ Crear Nueva Oportunidad</a></p>

    <table border="1" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Título</th>
                <th>Cliente</th>
                <th>Valor Estimado (€)</th>
                <th>Estado</th>
                <th>Fecha Creación</th>
                <th>Responsable</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($oportunidades)): ?>
                <tr><td colspan="7">No se encontraron oportunidades.</td></tr>
            <?php endif; ?>
            <?php foreach ($oportunidades as $op): ?>
                <tr>
                    <td><?php echo htmlspecialchars($op['titol']); ?></td>
                    <td><a href="index.php?controller=cliente&action=edit&id=<?php echo $op['id_client'] ?? ''; ?>"><?php echo htmlspecialchars($op['nombre_cliente']); ?></a></td>
                    <td><?php echo number_format($op['valor_estimat'], 2, ',', '.') . ' €'; ?></td>
                    <td><span style="font-weight: bold; color:
                        <?php
                        if ($op['estat'] == 'guanyada') echo 'green';
                        else if ($op['estat'] == 'perduda') echo 'red';
                        else echo 'blue';
                        ?>;"><?php echo ucfirst($op['estat']); ?></span>
                    </td>
                    <td><?php echo htmlspecialchars(substr($op['fecha_creacion'], 0, 10)); ?></td>
                    <td><?php echo htmlspecialchars($op['nombre_responsable']); ?></td>
                    <td>
                        <a href="index.php?controller=oportunidad&action=edit&id=<?php echo $op['id_oportunitat']; ?>">Ver/Editar</a>
                        | <a href="index.php?controller=oportunidad&action=delete&id=<?php echo $op['id_oportunitat']; ?>" onclick="return confirm('¿Seguro que deseas eliminar esta oportunidad?');">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>