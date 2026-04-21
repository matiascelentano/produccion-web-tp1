<?php
require_once '../../classes/Auto.php';
require_once '../../classes/Autorizacion.php';
require_once '../../classes/Paginador.php';

session_start();
Autorizacion::permisosUsuario();

if(isset($_GET['delete'])) {
    Auto::borrar($_GET['delete']['id']);
    header("Location: index.php");
    exit();
}
if(isset($_GET['edit'])) {
    header("Location: autoEdit.php?id=" . $_GET['edit']['id']);
    exit();
}

$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'id_auto';
$direction = strtoupper($_GET['direction'] ?? 'ASC');
$direction = $direction === 'DESC' ? 'DESC' : 'ASC';
$page = isset($_GET['page']) && ctype_digit($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$perPage = 10;
$totalAutos = Auto::contarAutos($search);
$paginador = new Paginador($totalAutos, $page, $perPage, 'index.php', ['search' => $search, 'sort' => $sort, 'direction' => $direction]);
$autos = Auto::buscarTodos($search, $paginador->getLimit(), $paginador->getOffset(), $sort, $direction);

function buildSortLink(string $column, string $label, string $currentSort, string $currentDirection, string $search): string {
    $nextDirection = ($currentSort === $column && $currentDirection === 'ASC') ? 'DESC' : 'ASC';
    $params = array_filter([
        'search' => $search,
        'sort' => $column,
        'direction' => $nextDirection,
    ], static fn($value) => $value !== '' && $value !== null);
    $url = 'index.php?' . http_build_query($params);
    $arrow = '';
    if ($currentSort === $column) {
        $arrow = $currentDirection === 'ASC' ? ' ▲' : ' ▼';
    }
    return '<a href="' . htmlspecialchars($url) . '" class="text-white text-decoration-none">' . htmlspecialchars($label) . $arrow . '</a>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <?php include './includes/styles.php'; ?>
</head>
<body>
    <?php include './includes/header.php'; ?>
    <main>
        <div class="d-flex flex-column p-0 m-0">
            <h1 class="text-center page-title">Listado de Autos</h1>
            <div class="container-md mb-3">
                <form method="get" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Buscar por marca o modelo..." value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn btn-outline-primary">Buscar</button>
                    <?php if (!empty($search)): ?>
                        <a href="index.php" class="btn btn-outline-secondary ms-2">Limpiar</a>
                    <?php endif; ?>
                </form>
            </div>
            <table class="table table-dark table-striped container-md">
                <tr>
                    <th><?= buildSortLink('id_auto', 'ID', $sort, $direction, $search) ?></th>
                    <th><?= buildSortLink('marca', 'Marca', $sort, $direction, $search) ?></th>
                    <th><?= buildSortLink('modelo', 'Modelo', $sort, $direction, $search) ?></th>
                    <th><?= buildSortLink('anio', 'Año', $sort, $direction, $search) ?></th>
                    <th><?= buildSortLink('kilometros', 'Kilómetros', $sort, $direction, $search) ?></th>
                    <th><?= buildSortLink('precio', 'Precio', $sort, $direction, $search) ?></th>
                    <th>Acciones</th>
                </tr>
                <?php foreach($autos as $auto): ?>
                    <tr>
                        <td><?= $auto['id_auto'] ?></td>
                        <td><?= $auto['marca_nombre'] ?></td>
                        <td><?= $auto['modelo'] ?></td>
                        <td><?= $auto['anio'] ?></td>
                        <td><?= $auto['kilometros'] === '0' || $auto['kilometros'] === 0 ? '0 (Nuevo)' : number_format($auto['kilometros'], 0, ',', '.') ?></td>
                        <td>$<?= number_format($auto['precio'], 2, ',', '.') ?></td>
                        <td>
                            <button class="btn btn-sm btn-primary my-2" onclick="location.href='autoEdit.php?id=' + <?= json_encode($auto['id_auto']) ?>">Editar</button>
                            <button class="btn btn-sm btn-danger my-2" onclick="location.href='index.php?delete[id]=' + <?= json_encode($auto['id_auto']) ?>">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?= $paginador->render() ?>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>