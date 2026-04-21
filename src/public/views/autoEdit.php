<?php
require_once '../../classes/Auto.php';
require_once '../../classes/Marca.php';
require_once '../../classes/Usuario.php';
require_once '../../classes/Autorizacion.php';
session_start();
Autorizacion::permisosAdmin();
Autorizacion::permisosUsuario();

if (isset($_GET['logout'])) {
    Usuario::logout();
}

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id = (int) $_GET['id'];
$auto = Auto::buscar($id);
if (!$auto) {
    header('Location: index.php');
    exit();
}

$marcas = Marca::buscarTodos();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idMarca = $_POST['idMarca'] ?? '';
    $modelo = trim($_POST['modelo'] ?? '');
    $anio = trim($_POST['anio'] ?? '');
    $kilometros = trim($_POST['kilometros'] ?? '');
    $precio = trim($_POST['precio'] ?? '');

    if ($idMarca === '' || $modelo === '' || $anio === '' || $kilometros === '' || $precio === '') {
        $error = 'Marca, modelo, año, kilómetros y precio son obligatorios.';
    } elseif (!ctype_digit($idMarca) || !ctype_digit($anio) || !ctype_digit($kilometros) || !is_numeric($precio)) {
        $error = 'Marca, año y kilómetros deben ser valores numéricos válidos. Precio debe ser un número.';
    } else {
        $data = [
            'idMarca' => (int) $idMarca,
            'modelo' => $modelo,
            'anio' => (int) $anio,
            'kilometros' => (int) $kilometros,
            'precio' => (float) $precio,
        ];

        if (Auto::actualizar($id, $data)) {
            $success = 'Auto actualizado correctamente.';
            $auto = Auto::buscar($id);
        } else {
            $error = 'No se pudo actualizar el auto. Intente nuevamente.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Auto</title>
    <?php include './includes/styles.php'; ?>
</head>
<body>
    <?php include './includes/header.php'; ?>
    <main>
        <div class="d-flex flex-column p-0 m-0">
            <h1 class="text-center page-title">Editar Auto</h1>
            <div class="container-md mb-3">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>
                
                <form method="post">
                    <div class="mb-3">
                        <label for="idMarca" class="form-label">Marca</label>
                        <select id="idMarca" name="idMarca" class="form-select" required>
                            <option value="">Seleccione una marca</option>
                            <?php foreach ($marcas as $marca): ?>
                                <option value="<?= htmlspecialchars($marca['id_marca']) ?>" <?= $auto['id_marca'] == $marca['id_marca'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($marca['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
    
                    <div class="mb-3">
                        <label for="modelo" class="form-label">Modelo</label>
                        <input type="text" id="modelo" name="modelo" class="form-control" value="<?= htmlspecialchars($_POST['modelo'] ?? $auto['modelo']) ?>" required>
                    </div>
    
                    <div class="mb-3">
                        <label for="anio" class="form-label">Año</label>
                        <input type="number" id="anio" name="anio" class="form-control" value="<?= htmlspecialchars($_POST['anio'] ?? $auto['anio']) ?>" required>
                    </div>
    
                    <div class="mb-3">
                        <label for="kilometros" class="form-label">Kilómetros</label>
                        <input type="number" id="kilometros" name="kilometros" class="form-control" value="<?= htmlspecialchars($_POST['kilometros'] ?? $auto['kilometros']) ?>" min="0" required>
                    </div>

                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio</label>
                        <input type="number" id="precio" name="precio" class="form-control" value="<?= htmlspecialchars($_POST['precio'] ?? $auto['precio']) ?>" step="0.01" min="0" required>
                    </div>
    
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    <a href="index.php" class="btn btn-secondary ms-2">Volver</a>
                </form>
            </div>
            
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
