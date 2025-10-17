<?php
require_once __DIR__ . '/../../others/config/ConfigDatabase.php';

class Consultorio {
    public PDO $pdo;
    public $connumero;
    public $connombre;
    public $condetalle;

    public function __construct()
    {
        $conexion = new BDConnect();
        $this->pdo = $conexion->establecerConexion();
    }

    public function setNumero($numero) {
        $this->connumero = $numero;
    }

    public function setNombre($nombre) {
        $this->connombre = $nombre;
    }
    
    public function setDetalle($detalle) {
        $this->condetalle = $detalle;
    }

    public function getNumero() {
        return $this->connumero;
    }

    public function getNombre() {
        return $this->connombre;
    }
    
    public function getDetalle() {
        return $this->condetalle;
    }

    public function consultorioExistente() {
        try {
            $sql = 'SELECT * FROM consultorios WHERE connumero = ?';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $this->getNombre()
            ]);

            $capture = $stmt->fetch();

            return $capture ?: false;
        } catch (PDOException $err) {
            echo "Ha ocurrido un error inesperado en la consulta de campos $err";
            return false;
        }
    }
}