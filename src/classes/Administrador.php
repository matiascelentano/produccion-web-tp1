<?php
require_once 'Usuario.php';
require_once 'Empleado.php';

class Administrador extends Usuario {
    public function __construct( string $nombre, string $email, string $password) {
        parent::__construct($nombre, $email, $password, "admin");
    }

    public static function registrarUsuario(string $nombre, string $apellido, string $email, string $password, string $rol) {
        if($rol == "admin"){
            $user = new Administrador($nombre, $email, $password);
        }else if($rol == "empleado") {
            $user = new Empleado($nombre, $email, $password);
        }else {
            throw new Exception("Rol no válido");
        }
        $db = DB::getConnection();
        $stmt = $db->prepare("INSERT INTO usuarios (nombre, apellido, email, password, rol) VALUES (:nombre, :apellido, :email, :password, :rol)");
        $stmt->bindValue(':nombre', $user->getNombre());
        $stmt->bindValue(':apellido', $apellido);
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':password', $user->getPassword());
        $stmt->bindValue(':rol', $user->getRol());
        $stmt->execute();
        header("Location: listaUsuarios.php");
        exit();
    }

    public static function actualizarUsuario($id, $nombre, $email, $password) {
        $db = DB::getConnection();
        $stmt = $db->prepare("UPDATE usuarios SET nombre = :nombre, email = :email, password = :password WHERE id = :id");
        $stmt->bindValue(':nombre', $nombre);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', $password);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        header("Location: admin.php");
    }

    public static function borrarUsuario($id) {
        $db = DB::getConnection();

        $stmt = $db->prepare("DELETE FROM usuarios WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        header("Location: admin.php");
    }
}