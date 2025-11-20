<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CRM Ilerna - Inicio de Sesión</title>
    </head>
<body>
    <div style="width: 300px; margin: 50px auto; padding: 20px; border: 1px solid #ccc;">
        <h2>Acceso al Sistema CRM</h2>

        <?php if (!empty($error)): ?>
            <p style="color: red; background-color: #fdd; padding: 5px; border: 1px solid red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="index.php?controller=auth&action=login" method="POST">
            <label for="nombre_usuario">Usuario:</label>
            <input type="text" id="nombre_usuario" name="nombre_usuario" required
                   placeholder="Ej: admin o venedor1" style="width: 95%; padding: 5px; margin-bottom: 10px;"><br>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required style="width: 95%; padding: 5px; margin-bottom: 20px;"><br>

            <button type="submit" style="width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; cursor: pointer;">Entrar</button>
        </form>
    </div>
</body>
</html>