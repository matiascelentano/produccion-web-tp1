<?php
require_once 'Gestionable.php';
require_once __DIR__ . '/../database/DB.php';

class Auto implements Gestionable {
    private $id;
    private $idMarca;
    private $modelo;
    private $anio;
    private $kilometros;
    private $precio;
    public static $autosCreadosSesion = 0;

    public function __construct($idMarca, $modelo, $anio, $kilometros = 0, $precio = 0) {
        $this->idMarca = $idMarca;
        $this->modelo = $modelo;
        $this->anio = $anio;
        $this->kilometros = $kilometros;
        $this->precio = $precio;
    }
    //Getters y Setters
    public function getIdMarca() {
        return $this->idMarca;
    }
    public function getModelo() {
        return $this->modelo;
    }
    public function getAnio() {
        return $this->anio;
    }
    public function getKilometros() {
        return $this->kilometros;
    }
    public function setIdMarca($idMarca) {
        $this->idMarca = $idMarca;
    }
    public function setModelo($modelo) {
        $this->modelo = $modelo;
    }
    public function setAnio($anio) {
        $this->anio = $anio;
    }
    public function setKilometros($kilometros) {
        $this->kilometros = $kilometros;
    }
    public function getPrecio() {
        return $this->precio;
    }
    public function setPrecio($precio) {
        $this->precio = $precio;
    }
    //Funciones de gestión de autos
    public function crear() {
        $db = DB::getConnection();
        $stmt = $db->prepare("INSERT INTO autos (id_marca, modelo, anio, kilometros, precio) VALUES (:idMarca, :modelo, :anio, :kilometros, :precio)");
        $stmt->bindValue(':idMarca', $this->idMarca);
        $stmt->bindValue(':modelo', $this->modelo);
        $stmt->bindValue(':anio', $this->anio);
        $stmt->bindValue(':kilometros', $this->kilometros, PDO::PARAM_INT);
        $stmt->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $result = $stmt->execute();
        if ($result) {
            self::$autosCreadosSesion++;
        }
        return $result;
    }

    public static function buscar($id) {
        $db = DB::getConnection();
        $stmt = $db->prepare("SELECT autos.id_auto, autos.id_marca, marcas.nombre as marca_nombre, autos.modelo, autos.anio, autos.kilometros, autos.precio FROM autos JOIN marcas ON autos.id_marca = marcas.id_marca WHERE autos.id_auto = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function buscarTodos($search = '', $limit = null, $offset = null, $sort = 'id_auto', $direction = 'ASC') {
        $db = DB::getConnection();
        $allowedSort = [
            'id_auto' => 'autos.id_auto',
            'marca' => 'marcas.nombre',
            'modelo' => 'autos.modelo',
            'anio' => 'autos.anio',
            'kilometros' => 'autos.kilometros',
            'precio' => 'autos.precio',
        ];
        $sortColumn = $allowedSort[$sort] ?? 'autos.id_auto';
        $direction = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';

        $query = "SELECT autos.id_auto, autos.id_marca, marcas.nombre as marca_nombre, autos.modelo, autos.anio, autos.kilometros, autos.precio FROM autos JOIN marcas ON autos.id_marca = marcas.id_marca";
        $params = [];
        //Se confirma si viene un parametro por URL para buscar por nombre o modelo, si viene se agrega a la consulta SQL, si no se muestra el listado completo de usuarios
        if (!empty($search)) {
            $query .= " WHERE marcas.nombre LIKE :search OR autos.modelo LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }

        $query .= " ORDER BY $sortColumn $direction";

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
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function borrar($id) {
        $db = DB::getConnection();
        $stmt = $db->prepare("DELETE FROM autos WHERE id_auto = :id");
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    public static function actualizar(int $id, array $data) {
        $db = DB::getConnection();
        $stmt = $db->prepare("UPDATE autos SET id_marca = :idMarca, modelo = :modelo, anio = :anio, kilometros = :kilometros, precio = :precio WHERE id_auto = :id");
        $stmt->bindValue(':idMarca', $data['idMarca']);
        $stmt->bindValue(':modelo', $data['modelo']);
        $stmt->bindValue(':anio', $data['anio']);
        $stmt->bindValue(':kilometros', $data['kilometros'], PDO::PARAM_INT);
        $stmt->bindValue(':precio', $data['precio'], PDO::PARAM_STR);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    public static function contarAutos($search = '') {
        $db = DB::getConnection();
        $query = "SELECT COUNT(*) AS total FROM autos JOIN marcas ON autos.id_marca = marcas.id_marca";
        $params = [];

        if (!empty($search)) {
            $query .= " WHERE marcas.nombre LIKE :search OR autos.modelo LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }

        $stmt = $db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['total'] : 0;
    }
}