<?php
require_once '../../classes/Usuario.php';
session_start();
if ($_POST) {
    Usuario::login($_POST['inputEmail'], $_POST['inputPassword']);
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="../styles/login.css">
        <?php include './includes/styles.php'; ?>
    </head>
    <body>
        <main class="d-flex flex-column align-items-center">
            <div class="container d-flex flex-column align-items-center" id="login-container">
                <h1 class="mt-5 text-center">Iniciar Sesión</h1>
                <form class="d-flex flex-column align-items-center my-5 container-sm" method="POST" action="">
                    <?php if (isset($_SESSION['login_error'])): ?>
                        <p class="text-danger">
                            <?php echo $_SESSION['login_error']; ?>
                        </p>
                        <?php unset($_SESSION['login_error']); ?>
                        <?php else: ?>
                        <p>Ingrese sus credenciales para iniciar sesión</p>
                    <?php endif; ?>
                    <div class="mb-3 w-100" id="emailField">
                        <label for="inputEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="inputEmail" name="inputEmail" aria-describedby="emailHelp" placeholder="Ingrese su email">

                    </div>
                    <div class="mb-3 w-100" id="passwordField">
                        <label for="inputPassword" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="inputPassword" name="inputPassword" placeholder="Ingrese su contraseña">
                    </div>
                    <button type="submit" class="btn btn-primary" id="login-button">Ingresar</button>
                </form>
            </div>
        </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    </body>
</html>