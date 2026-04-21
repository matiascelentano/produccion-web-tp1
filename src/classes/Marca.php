<?php
require_once __DIR__ . '/../database/DB.php';

class Marca {
    private $id;
    private $nombre;

    public function __construct($nombre) {
        $this->nombre = $nombre;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public static function buscarTodos() {
        $db = DB::getConnection();
        $stmt = $db->prepare("SELECT * FROM marcas");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}