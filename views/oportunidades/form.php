<!DOCTYPE html>
<html lang="es">
<head>
    <title><?php echo isset($oportunidad) ? 'Editar Oportunidad' : 'Crear Oportunidad'; ?></title>
</head>
<body>
    <h1><?php echo isset($oportunidad) ? '✏️ Editar Oportunidad' : '➕ Crear Nueva Oportunidad'; ?></h1>
    <p><a href="index.php?controller=oportunidad&action=index">Volver al Listado de Oportunidades</a> | <a href="index.php?controller=auth&action=logout">Cerrar Sesión</a></p>
    <hr>

    <form action="index.php?controller=oportunidad&action=<?php echo isset($oportunidad) ? 'edit&id=' . $oportunidad['id_oportunitat'] : 'create'; ?>" method="POST">
        <input type="hidden" name="id_oportunitat" value="<?php echo $oportunidad['id_oportunitat'] ?? ''; ?>">

        <label for="titol">Título (*):</label>
        <input type="text" name="titol" id="titol" value="<?php echo htmlspecialchars($oportunidad['titol'] ?? ''); ?>" required><br><br>

        <label for="id_client">Cliente (*):</label>
        <select name="id_client" id="id_client" required>
            <option value="">Seleccione un cliente</option>
            <?php foreach ($clientes_list as $cliente): ?>
                <option value="<?php echo $cliente['id_client']; ?>"
                        <?php if (($oportunidad['id_client'] ?? '') == $cliente['id_client']) echo 'selected'; ?>
                >
                    <?php echo htmlspecialchars($cliente['nom_complet']); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="valor_estimat">Valor Estimado (€):</label>
        <input type="number" step="0.01" name="valor_estimat" id="valor_estimat" value="<?php echo htmlspecialchars($oportunidad['valor_estimat'] ?? 0.00); ?>"><br><br>

        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" id="descripcion" rows="4" cols="50"><?php echo htmlspecialchars($oportunidad['descripcio'] ?? ''); ?></textarea><br><br>

        <label for="estat">Estado (*):</label>
        <select name="estat" id="estat" required>
            <option value="progreso" <?php if (($oportunidad['estat'] ?? '') === 'progreso') echo 'selected'; ?>>En Progreso</option>
            <option value="guanyada" <?php if (($oportunidad['estat'] ?? '') === 'guanyada') echo 'selected'; ?>>Ganada</option>
            <option value="perduda" <?php if (($oportunidad['estat'] ?? '') === 'perduda') echo 'selected'; ?>>Perdida</option>
        </select><br><br>

        <?php if ($rol === 'administrador'):
            // Cargar usuarios para el select
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
                            $selected_id = $oportunidad['usuario_responsable'] ?? $user_id;
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

        <button type="submit"><?php echo isset($oportunidad) ? 'Guardar Cambios' : 'Crear Oportunidad'; ?></button>
        <a href="index.php?controller=oportunidad&action=index">Cancelar</a>
    </form>
</body>
</html>