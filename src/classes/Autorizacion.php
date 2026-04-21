<?php
class Autorizacion {
    public static function permisosAdmin() {
        if(isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] !== 'admin') {
            header("Location: index.php");
            exit();
        }
    }
    public static function permisosUsuario() {
        if(!isset($_SESSION['usuario'])) {
            header("Location: login.php");
            exit();
        }
    }
}