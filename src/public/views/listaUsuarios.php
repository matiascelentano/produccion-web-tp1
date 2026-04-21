<?php
require_once '../../classes/Usuario.php';
require_once '../../classes/Paginador.php';
require_once '../../classes/Autorizacion.php';
session_start();
Autorizacion::permisosAdmin();
Autorizacion::permisosUsuario();

if(isset($_GET['delete'])) {
    Usuario::borrar($_GET['delete']['id']);
    header("Location: listaUsuarios.php");
    exit();
}

$search = $_GET['search'] ?? '';
$page = isset($_GET['page']) && ctype_digit($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$perPage = 10;
$totalUsers = Usuario::contarUsuarios($search);
$paginador = new Paginador($totalUsers, $page, $perPage, 'listaUsuarios.php', ['search' => $search]);
$userList = Usuario::buscarTodos($search, $paginador->getLimit(), $paginador->getOffset());
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Usuarios</title>
    <?php include './includes/styles.php'; ?>
</head>
<body>
    <?php include './includes/header.php'; ?>
    <main>
         <h1 class="text-center page-title">Lista de Usuarios</h1>
         <div class="d-flex flex-column p-0 m-0">
            <div class="container-md mb-3">
                <form method="get" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Buscar por nombre, apellido o email..." value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn btn-outline-primary">Buscar</button>
                    <?php if (!empty($search)): ?>
                        <a href="listaUsuarios.php" class="btn btn-outline-secondary ms-2">Limpiar</a>
                    <?php endif; ?>
                </form>
            </div>
            <table class="table table-dark table-striped container-md">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($userList as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id_usuario']) ?></td>
                        <td><?= htmlspecialchars($user['nombre']) ?></td>
                        <td><?= htmlspecialchars($user['apellido'] ?? '') ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['rol']) ?></td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="location.href='usuarioEdit.php?id=' + <?= json_encode($user['id_usuario']) ?>">Editar</button>
                            <button class="btn btn-sm btn-danger" onclick="location.href='listaUsuarios.php?delete[id]=' + <?= json_encode($user['id_usuario']) ?>">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?= $paginador->render() ?>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>