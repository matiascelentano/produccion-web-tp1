<?php
require_once 'Usuario.php';

class Empleado extends Usuario {
    public function __construct( string $nombre, string $email, string $password) {
        parent::__construct($nombre, $email, $password, "empleado");
    }
}