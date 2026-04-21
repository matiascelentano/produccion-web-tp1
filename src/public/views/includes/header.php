<?php
require_once '../../classes/Usuario.php';
if(isset($_GET['logout'])) {
    Usuario::logout();
}
echo '<header>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <img src="./../assets/logo.png" alt="Logo" id="logo-header">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="" aria-current="page" href="index.php">Listado de Autos</a>
                        </li>
                        <li class="nav-item">
                            <a class="" href="crearAuto.php">Registrar Auto</a>
                        </li>';

if(isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] === 'admin') {
    echo '<li class="nav-item">
        <a class="" href="listaUsuarios.php">Listado de Usuarios</a>
    </li>';
    echo '<li class="nav-item">
        <a class="" href="crearUsuario.php">Registrar Usuario</a>
    </li>';
};
echo '
        <li class="nav-item">
            <a class="" href="?logout=1" id="logout-link">Cerrar Sesion</a>
        </li>
                </ul>
            </div>
        </div>
    </nav>
</header>';