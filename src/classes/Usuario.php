<?php
require_once 'Autenticable.php';
require_once 'Gestionable.php';
require_once __DIR__ . '/../database/DB.php';

abstract class Usuario implements Autenticable, Gestionable {
    private int $id;

    private string $nombre;

    private string $email;

    private string $password;

    private string $rol;

    public function __construct(string $nombre, string $email, string $password, string $rol) {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->password = $password;
        $this->rol = $rol == "admin" || $rol == "empleado"? $rol : throw new Exception("Rol no válido");
    }
    //Getters y Setters

    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getRol() {
        return $this->rol;
    }

    public function setNombre(string $nombre) {
        $this->nombre = $nombre;
    }

    public function setEmail(string $email) {
        $this->email = $email;
    }

    public function setPassword(string $password) {
        $this->password = $password;
    }

    public function setRol(string $rol) {
        if($rol == "admin" || $rol == "empleado") {
            $this->rol = $rol;
        } else {
            throw new Exception("Rol no válido");
        }
    }

    //Funciones de autenticación y gestión de usuarios

    public static function login(string $email, string $password) {
        $db = DB::getConnection();
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();
        if ($user && $password == $user['password']) {
            $_SESSION['usuario'] = $user;
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['login_error'] = "Email o contraseña incorrectos";
            header("Location: login.php");
            exit();
        }
    }

    public static function logout() {
        session_destroy();
        header("Location: login.php");
        exit();
    }

    public function crear() {
        if($_SESSION['usuario']['rol'] != "admin") {
            throw new Exception("No tienes permisos para crear usuarios");
        }else {
            $db = DB::getConnection();
            $stmt = $db->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (:nombre, :email, :password, :rol)");
            $stmt->bindValue(':nombre', $this->nombre);
            $stmt->bindValue(':email', $this->email);
            $stmt->bindValue(':password', $this->password);
            $stmt->bindValue(':rol', $this->rol);
            $stmt->execute();
        }
    }

    public static function buscar(int $id) {
        $db = DB::getConnection();
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE id_usuario = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function buscarTodos($search = '', $limit = null, $offset = null) {
        $db = DB::getConnection();
        $query = "SELECT * FROM usuarios";
        $params = [];
        //Se confirma si viene un parametro por URL para buscar por nombre, apellido o email, si viene se agrega a la consulta SQL, si no se muestra el listado completo de usuarios
        if (!empty($search)) {
            $query .= " WHERE nombre LIKE :search OR apellido LIKE :search OR email LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }

        $query .= " ORDER BY id_usuario";

        if ($limit !== null && $offset !== null) {
            $query .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        if ($limit !== null && $offset !== null) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function actualizar(int $id, array $data) {
        $db = DB::getConnection();
        $stmt = $db->prepare("UPDATE usuarios SET nombre = :nombre, apellido = :apellido, email = :email, password = :password, rol = :rol WHERE id_usuario = :id");
        $stmt->bindValue(':nombre', $data['nombre']);
        $stmt->bindValue(':apellido', $data['apellido'] ?? '');
        $stmt->bindValue(':email', $data['email']);
        $stmt->bindValue(':password', $data['password']);
        $stmt->bindValue(':rol', $data['rol']);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }

    public static function borrar(int $id) {
        $db = DB::getConnection();
        $stmt = $db->prepare("DELETE FROM usuarios WHERE id_usuario = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }

    public static function contarUsuarios($search = '') {
        $db = DB::getConnection();
        $query = "SELECT COUNT(*) as total FROM usuarios";
        $params = [];

        if (!empty($search)) {
            $query .= " WHERE nombre LIKE :search OR apellido LIKE :search OR email LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }

        $stmt = $db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        $result = $stmt->fetch();
        return $result ? (int)$result['total'] : 0;
    }      
}