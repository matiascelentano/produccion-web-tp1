<?php
require_once '../../classes/Administrador.php';
require_once '../../classes/Autorizacion.php';
session_start();
Autorizacion::permisosUsuario();
Autorizacion::permisosAdmin();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $rol = $_POST['rol'] ?? '';

    if ($nombre === '' || $email === '' || $password === '' || $rol === '') {
        $error = 'Nombre, email, contraseña y rol son obligatorios.';
    } elseif (!in_array($rol, ['admin', 'empleado'], true)) {
        $error = 'Rol no válido.';
    } else{
        Administrador::registrarUsuario($nombre, $apellido, $email, $password, $rol);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuario</title>
    <?php include './includes/styles.php'; ?>
</head>
<body>
    <?php include './includes/header.php'; ?>
    <main>
        <div class="d-flex flex-column p-0 m-0">
            <h1 class="text-center page-title">Registrar Usuario</h1>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="post" class="container-md">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>" placeholder="Ingrese el nombre del usuario">
                    </div>
                    
                    <div class="mb-3">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" id="apellido" name="apellido" class="form-control" value="<?= htmlspecialchars($_POST['apellido'] ?? '') ?>" placeholder="Ingrese el apellido del usuario">
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="Ingrese el email del usuario">
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Ingrese la contraseña del usuario">
                    </div>
                    
                    <div class="mb-3">
                        <label for="rol" class="form-label">Rol</label>
                        <select id="rol" name="rol" class="form-select" >
                            <option value="">Seleccionar rol</option>
                            <option value="admin" <?= (isset($_POST['rol']) && $_POST['rol'] === 'admin') ? 'selected' : '' ?>>Admin</option>
                            <option value="empleado" <?= (isset($_POST['rol']) && $_POST['rol'] === 'empleado') ? 'selected' : '' ?>>Empleado</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Registrar</button>
                    <a href="listaUsuarios.php" class="btn btn-secondary ms-2">Volver</a>
                </form>
            </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>