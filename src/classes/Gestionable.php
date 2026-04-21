<?php
interface Gestionable {
    public function crear();
    public static function buscar(int $id);
    public static function buscarTodos($search = '');
    public static function actualizar(int $id, array $data);
    public static function borrar(int $id);
}