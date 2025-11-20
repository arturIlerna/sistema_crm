<?php
// views/dashboard.php
$rol = get_current_user_role();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Dashboard CRM</title>
</head>
<body>
    <h1>Bienvenido, <?php echo $_SESSION['user_name']; ?> (Rol: <?php echo ucfirst($rol); ?>)</h1>
    <p><a href="index.php?controller=auth&action=logout">Cerrar Sesión</a></p>
    <hr>
    
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if ($rol === 'administrador'): ?>
        <h2>Resumen del Sistema (Administrador)</h2>
        <div style="display: flex; gap: 20px;">
            <div style="border: 1px solid #ccc; padding: 15px;">
                <h3>Clientes Totales:</h3>
                <p style="font-size: 24px; font-weight: bold;"><?php echo $resumen['clientes'] ?? 0; ?></p>
            </div>
            
            <div style="border: 1px solid #ccc; padding: 15px;">
                <h3>Tareas Pendientes:</h3>
                <p style="font-size: 24px; font-weight: bold;"><?php echo $resumen['tareas_pendientes'] ?? 0; ?></p>
            </div>
            
            <div style="border: 1px solid #ccc; padding: 15px;">
                <h3>Oportunidades por Estado:</h3>
                <?php if (!empty($resumen['oportunidades'])): ?>
                    <ul>
                        <?php foreach ($resumen['oportunidades'] as $op): ?>
                            <li><?php echo ucfirst($op['estat']); ?>: **<?php echo $op['total']; ?>**</li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No hay oportunidades registradas.</p>
                <?php endif; ?>
            </div>
        </div>
        <hr>
        <h3>Administración</h3>
        <p><a href="index.php?controller=usuario&action=index">Gestionar Usuarios (CRUD)</a></p>
    <?php endif; ?>

    <h2>Módulos de Gestión</h2>
    <ul>
        <li><a href="index.php?controller=cliente&action=index">Módulo de Clientes</a></li>
        <li><a href="index.php?controller=oportunidad&action=index">Módulo de Oportunidades</a></li>
        <li><a href="index.php?controller=tarea&action=index">Módulo de Actividades o Tareas</a></li>
    </ul>
</body>
</html>