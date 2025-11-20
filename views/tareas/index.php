<!DOCTYPE html>
<html lang="es">
<head>
    <title>Módulo de Tareas</title>
</head>
<body>
    <h1>Módulo de Actividades o Tareas</h1>
    <p><a href="index.php?controller=dashboard&action=index">Volver al Dashboard</a> | <a href="index.php?controller=auth&action=logout">Cerrar Sesión</a></p>
    <hr>

    <?php if (isset($mensaje)): ?>
        <p style="color: green; font-weight: bold;"><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <p style="color: red; font-weight: bold;"><?php echo $error; ?></p>
    <?php endif; ?>

    <h2>➕ Crear Nueva Tarea</h2>
    <form action="index.php?controller=tarea&action=index" method="POST">
        <input type="hidden" name="action" value="create">

        <label for="id_oportunitat">Relacionar con Oportunidad:</label>
        <select name="id_oportunitat" id="id_oportunitat">
            <option value="">(Tarea general)</option>
            <?php foreach ($oportunidades_list as $op): ?>
                <option value="<?php echo $op['id_oportunitat']; ?>">
                    <?php echo htmlspecialchars($op['titol']); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="descripcion">Descripción de la Tarea (*):</label>
        <textarea name="descripcion" id="descripcion" required></textarea><br><br>

        <label for="fecha">Fecha Límite (opcional):</label>
        <input type="date" name="fecha" id="fecha"><br><br>

        <button type="submit">Crear Tarea</button>
    </form>

    <hr>

    <h2>Listado de Tareas</h2>
    <p>
        <a href="index.php?controller=tarea&action=index&pendientes=true">Mostrar Solo Pendientes</a> |
        <a href="index.php?controller=tarea&action=index">Mostrar Todos (Pendientes y Completadas)</a>
    </p>

    <table border="1" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Descripción</th>
                <th>Fecha Límite</th>
                <th>Relacionado (Oportunidad)</th>
                <th>Cliente</th>
                <th>Responsable</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($tareas)): ?>
                <tr><td colspan="7">No se encontraron tareas <?php echo $filtro_pendiente ? 'pendientes' : ''; ?>.</td></tr>
            <?php endif; ?>
            <?php foreach ($tareas as $t): ?>
                <tr>
                    <td><?php echo htmlspecialchars($t['descripcio']); ?></td>
                    <td><?php echo $t['fecha'] ? htmlspecialchars($t['fecha']) : 'N/A'; ?></td>
                    <td><?php echo htmlspecialchars($t['op_titulo'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($t['cliente_nombre'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($t['responsable_nombre']); ?></td>
                    <td>
                        <span style="font-weight: bold; color: <?php echo $t['estat'] == 'pendent' ? 'orange' : 'green'; ?>">
                            <?php echo ucfirst($t['estat']); ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($t['estat'] == 'pendent'): ?>
                            <a href="index.php?controller=tarea&action=complete&id=<?php echo $t['id_tarea']; ?>" onclick="return confirm('¿Marcar como completada?');">Marcar Completada</a>
                        <?php else: ?>
                            *Completada*
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>