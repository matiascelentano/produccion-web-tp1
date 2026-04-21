<?php
require_once '../../classes/Usuario.php';
require_once '../../classes/Autorizacion.php';
session_start();
Autorizacion::permisosAdmin();
Autorizacion::permisosUsuario();


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: listaUsuarios.php');
    exit();
}

$id = (int) $_GET['id'];
$user = Usuario::buscar($id);
if (!$user) {
    header('Location: listaUsuarios.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $rol = $_POST['rol'] ?? '';

    if ($nombre === '' || $email === '' || $rol === '') {
        $error = 'Nombre, email y rol son obligatorios.';
    } else {
        if ($password === '') {
            $password = $user['password'];
        }

        $data = [
            'nombre' => $nombre,
            'email' => $email,
            'password' => $password,
            'rol' => $rol,
            'apellido' => $apellido,
        ];

        Usuario::actualizar($id, $data);
        $user = Usuario::buscar($id);
        $success = 'Usuario actualizado correctamente.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <?php include './includes/styles.php'; ?>
</head>
<body>
    <?php include './includes/header.php'; ?>
    <main>
        <div class="d-flex flex-column p-0 m-0">
            <h1 class="page-title text-center">Editar Usuario</h1>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <div class="container-md mb-3">
                <form method="post">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" value="<?= htmlspecialchars($user['nombre']) ?>" required>
                    </div>

                    <?php if (array_key_exists('apellido', $user)): ?>
                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" id="apellido" name="apellido" class="form-control" value="<?= htmlspecialchars($user['apellido']) ?>">
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Dejar vacío para mantener la contraseña actual">
                    </div>

                    <div class="mb-3">
                        <label for="rol" class="form-label">Rol</label>
                        <select id="rol" name="rol" class="form-select" required>
                            <option value="admin" <?= $user['rol'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="empleado" <?= $user['rol'] === 'empleado' ? 'selected' : '' ?>>Empleado</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    <a href="listaUsuarios.php" class="btn btn-secondary ms-2">Volver al listado</a>
                </form>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>