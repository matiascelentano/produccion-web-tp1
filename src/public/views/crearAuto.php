<?php
require_once '../../classes/Auto.php';
require_once '../../classes/Marca.php';
require_once '../../classes/Autorizacion.php';
session_start();
Autorizacion::permisosUsuario();

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
        $auto = new Auto((int)$idMarca, $modelo, (int)$anio, (int)$kilometros, (float)$precio);
        if ($auto->crear()) {
            $success = 'Auto registrado correctamente.';
        } else {
            $error = 'No se pudo registrar el auto. Intente nuevamente.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Auto</title>
    <?php include './includes/styles.php'; ?>
</head>
<body>
    <?php include './includes/header.php'; ?>
    <main>
        <div class="d-flex flex-column p-0 m-0">
            <h1 class="text-center page-title">Registrar Auto</h1>
    
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
    
            <form method="post" class="container-md">
                <div class="mb-3">
                    <label for="idMarca" class="form-label">Marca</label>
                    <select id="idMarca" name="idMarca" class="form-select" >
                        <option value="">Seleccione una marca</option>
                        <?php foreach ($marcas as $marca): ?>
                            <option value="<?= htmlspecialchars($marca['id_marca']) ?>" <?= isset($_POST['idMarca']) && $_POST['idMarca'] == $marca['id_marca'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($marca['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
    
                <div class="mb-3">
                    <label for="modelo" class="form-label">Modelo</label>
                    <input type="text" id="modelo" name="modelo" class="form-control" value="<?= htmlspecialchars($_POST['modelo'] ?? '') ?>" placeholder="Ingrese el modelo del auto" >
                </div>
    
                <div class="mb-3">
                    <label for="anio" class="form-label">Año</label>
                    <input type="number" id="anio" name="anio" class="form-control" value="<?= htmlspecialchars($_POST['anio'] ?? '') ?>" placeholder="Ingrese el año de fabricación del auto" >
                </div>

                <div class="mb-3">
                    <label for="kilometros" class="form-label">Kilómetros</label>
                    <input type="number" id="kilometros" name="kilometros" class="form-control" value="<?= htmlspecialchars($_POST['kilometros'] ?? '') ?>" placeholder="Ingrese los kilómetros del auto" min="0">
                </div>

                <div class="mb-3">
                    <label for="precio" class="form-label">Precio</label>
                    <input type="number" id="precio" name="precio" class="form-control" value="<?= htmlspecialchars($_POST['precio'] ?? '') ?>" placeholder="Ingrese el precio del auto" step="0.01" min="0">
                </div>

                <button type="submit" class="btn btn-primary">Registrar</button>
                <a href="index.php" class="btn btn-secondary ms-2">Volver</a>
            </form>
        </div>   
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>